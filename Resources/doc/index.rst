Main Concepts
---------------------

First of all there are few concept about the architecture that must be pointed out for the sake of a good understanding.
OryzoneMediaStorageBundle revolves around 6 foundamental elements that must be composed togheter: **Provider**, **Filesystem**, **Cdn**, **Naming Strategy**, **Variant**, and ultimately **Context**. Each of these parts has been thought to be fully extendable and customizable even if the bundle offers some defaults that should be sufficient in most cases.

Provider
======
A provider is a way to abstract the logic behind a certain type of media. The bundle offers a series of default providers to store the most common media types (and their metadata): simple files (no processing), images (with possibility to crop and convert the source file format), external videos (actually only youtube and vimeo are supported).

Filesystem
=========
A thin abstraction built upon Gaufrette that is used to configure where your files will be stored.

Cdn
===
Allows you to define the logic behind the public URL of your files. By default the bundle offers 2 different Cdn strategies:

* **Local**: useful when you store the files in the same server that hosts your application code. Allows you to define a prefix (generally your public relative path) to your media ( eg. "/public/images/" ) 
* **Remote**: mostly used when you have a real CDN like Amazon S3 or Rackspace Cloud Files. In this case you can configure the CDN abstraction just by giving the url of your Amazon bucket or Rackspace container.

Naming Strategy
============
Contains the logic behind your file names. Which name will be have a picture uploaded by a user? Naming Strategy decides it! By default the bundle offers  a naming strategy that generates names like the following: ``avatar-user-loige-5345gf54324_sXS`` but you can obviously write your custom naming strategies if you like.

Variant
=====
Each stored media can have different variants. Imagine you need to store an image and need different variants to be generated from the original file (you may want a scaled version, a cropped one and a square preview). While your media remains one (a single entry in your database) you will have different files associated to it (one for each variant). Variants can be easily defined in the configuration as a tree and are processed accordingly.
The following picture shows a possible scenario: 

.. image:: http://i.imgur.com/oIjQql7.png
   :alt: Variants tree example
   :align: center

In this scenario your would be able give a source image file to the media storage library (e.g. by upload) and it will generates the DEFAULT variant, so it will generates the PREVIEW and THUMBNAIL variants by starting from the previously generated DEFAULT variant.
Obviously the tree is not limited to one-level nesting, you can have more nested variants if needed.

Context
======
Contexts are like glue: a context allows you to put things together for a specific need. For example suppose you want to store users avatar image in your website: you will need to define an *avatar* context. In the context you must specify which *provider* handles the files (in this case you can use the ``image`` provider), which *variants* will be generated for each avatar, the *naming strategy* you want to use to name all the generated files, which *filesystem* and which *Cdn*
to use to store and locate the files.
Obviously you can create as much contexts as you need (avatar, uploaded pictures, downloadable files, youtube attached videos and so on...) the only limitation is that each context can use a single provider.

Now you should have got a idea of the whole structure let's go with the configuration.

Configuration
-------------------
This complex structure should not be defined manually by instancing and connecting all related class but can be defined simply within the Symfony configuration. I will provide a sample configuration to go straight to the point. I suggest you to create a separate configuration file (e.g. ``mediastorage.yml``) and attach it to your main ``config.yml`` this way:

.. code-block:: yaml

  # config.yml
  imports:
      - { resource: mediastorage.yml }

Scenario: suppose you are building a website were registered user can attach youtube videos the like. So each user have an avatar and he can post youtube videos, we need 2 separate contexts: ``avatar`` and ``video``. You would like to store the files on the local filesystem (you will not use any external storage like amazon S3 and need to configure a local CDN to locate your files).

Follows a sample configuration:

.. code-block:: yaml

  # mediastorage.yml
  knp_gaufrette:
      adapters:
          avatar_adapter:
                  local:
                          directory: '%kernel.root_dir%/../web/img/avatar'
          video_adapter:
                  local:
                          directory: '%kernel.root_dir%/../web/img/video'
      filesystems:
          avatar_filesystem:
              adapter:    avatar_adapter
          video_filesystem:
              adapter:    video_adapter

  oryzone_media_storage:
      db_driver: orm # to use doctrine orm drivers, you can also use 'mongodb' (optional from 2.0.1)
      cdns:
          avatar_cdn:
                  local: { path: '/img/avatar/' }
          video_cdn:
                  local: { path: '/img/video/' }
      contexts:
          avatar:
              provider: image
              filesystem: avatar_filesystem
              cdn: avatar_cdn
              namingStrategy: slugged # the default (and actually the only one) available naming strategy
              variants: #defines the variants tree for the avatar images
                  big:
                      process: { width: 400, resize: proportional, format: jpg, quality: 90 }
                  small:
                      process: { width: 150, resize: proportional, format: jpg, quality: 90 }
                      parent: big
                  square:
                      process: { width: 100, height: 100, resize: crop, format: jpg, quality: 80 }
                      parent: big
          video:
              provider: youtube
              filesystem: video_filesystem
              cdn: video_cdn
              variants: #variants are needed here because the youtube provider will download the video preview files and may process them generating different variants
                  proportional:
                      process: { width: 500, resize: proportional, format: jpg, quality: 90 }
                  square:
                      process: { width: 220, height: 220, resize: crop, format: jpg, quality: 80 }
                      parent: big


The first part of the configuration (``knp_gaufrette``) is related to gaufrette (please read the `KnpGaufretteBundle`_ documentations if you need more information about it).

Note: Obviously you can write your configuration in XML if you prefer (but I haven't tested it still).

Define your models
----------------------------
The OryzoneMediaStorageBundle actually supports Doctrine2 as data persistance mechanism and allows you to use Doctrine ORM and MongoDB.

The bundle offers two base abstract ``Media`` classes that you can extend to define your models. The only missing feature is the id, as you may want to handle ids in your own way (auto-increment, auto-generation, manual insertion). So you need to implement the method ``getId()`` in your concrete implementations.

For Doctrine Orm
=============

Define your entity by extending the ``\Oryzone\Bundle\MediaStorageBundle\Entity\Media`` class.

Example:

.. code-block:: php

  <?php

  namespace Acme\Bundle\DemoBundle\Entity;

  use Doctrine\ORM\Mapping as ORM;

  /**
   * Acme\Bundle\DemoBundle\Entity\Media
   *
   * @ORM\Table()
   * @ORM\Entity()
   */
  class Media extends BaseMedia
  {
      /**
       * @var integer $id
       *
       * @ORM\Column(name="id", type="integer")
       * @ORM\Id
       * @ORM\GeneratedValue(strategy="AUTO")
       */
      protected $id;
  
      /**
       * {@inheritDoc}
       */
      public function getId()
      {
          return $this->id;
      }
  }


For Doctrine MongoDB
====================

Define your document by extending the ``\Oryzone\Bundle\MediaStorageBundle\Document\Media`` class.



Create, Update and delete a media
---------------------------------------------------
That's quite enough with configuration, let's get coding now!

Store a new media
===============

Here's a quick example on how to create (store) a new Media in your controller code:

.. code-block:: php

  <?php
  // ... inside some action method of some controller
  
  // Store an avatar (and all its variants)
  $image = __DIR__ . '/../some/image/file.jpg';
  $avatar = new \Acme\Bundle\DemoBundle\Entity\Media($image, 'avatar');
  $avatar->setName('sample avatar');
  $this->get('media_storage')->store($avatar);
  // if you don't need the original file anymore you can delete it (you will now use generated variants)
  @unlink($image);

As you can see you will not need to call doctrine directly (it's done out of the box).

Update a media
============
Suppose you want to change the image and the title of an already stored avatar.

.. code-block:: php

  <?php
  // ... inside some action method of some controller
  
  $newImage = __DIR__ . '/../some/new/image/file.jpg';
  
  //retrieve your avatar instance someway with doctrine
  $avatar = $this->getDoctrine()->getManager('AcmeDemoBundle:Media')->findOneById($someId) ;
  $avatar->setContent($newImage);
  $avatar->setName('new sample avatar');
  $this->get('media_storage')->update($avatar);
  
  @unlink($newImage);


Remove a media
===========
Suppose you want to delete an avatar.

.. code-block:: php

  <?php
  // ... inside some action method of some controller
  
  //retrieve your avatar instance someway with doctrine
  $avatar = $this->getDoctrine()->getManager('AcmeDemoBundle:Media')->findOneById($someId) ;
  $this->get('media_storage')->remove($avatar);

Note: by deleting a media all it's previously generated files (variants) will be deleted from the filesystem.


Display your Media in the view
--------------------------------------------
Ok you know how to create, edit and remove medias! Cool! Now you need to show them in your view.

Retrieve the url
===========
Sometimes to show a media is sufficient to know its URL.
If you are inside a controller you can obtain the url of a media this way:

.. code-block:: php
  
  <?php
  // ... inside some action method of some controller
  
  //retrieve your avatar instance someway with doctrine
  $avatar = $this->getDoctrine()->getManager('AcmeDemoBundle:Media')->findOneById($someId) ;
  $url = $this->get('media_storage')->getUrl($avatar, 'variantName');

Obviously each variant file has its own url so you need to pass the name of the variant you want to use as second argument to the ``getUrl()`` method.

If you're using twig you can use the filter ``mediaUrl(variantName)`` to obtain the url of a given media variant file. Example:

.. code-block:: html+jinja

  {# avatar is a Media instance passed to the template #}
  
  <img class="avatar" src="{{ avatar|mediaUrl('variantName') }}"/>

Render the media
=============
Rendering is an advanced function that may speed up the proper rendering of certain media files.
Each provider has its own render method specialized to construct the html code for its media type: the image provider is specialized to render ``img`` tags, the youtube provider will render the youtube embed code and so on.

If you need to render the html of an image you can user the ``render(Media $media, $variantName, $options = array())`` method:

.. code-block:: php

  // ... inside some action method of some controller
  
  //retrieve your avatar instance someway with doctrine
  $avatar = $this->getDoctrine()->getManager('AcmeDemoBundle:Media')->findOneById($someId) ;
  $avatarHTML = $this->get('media_storage')->render($avatar, 'variantName');

If you need to do it within a Twig template (best choice) you can use the ``mediaRender(variantName)`` filter:

.. code-block:: html+jinja

  {# avatar is a Media instance passed to the template #}
  
  <div class="user-avatar">
      {{ avatar|mediaRender('variantName') }}
  </div>

This will generate an ``img`` tag with proper ``width``, ``height``, and ``alt`` attributes.


Diving deep
----------

* `Use forms`_ (to be written)
* `Events`_ (to be written)
* `Write custom naming strategy`_ (to be written)
* `Write custom provider`_ (to be written)
* `Write custom cdn`_ (to be written)
* `Write custom events adapter`_ (to be written)
* `Write custom persistence adapter`_ (to be written)






.. _KnpGaufretteBundle: https://github.com/KnpLabs/KnpGaufretteBundle

.. _Use forms: forms.rst
.. _Events: events.rst
.. _Write custom naming strategy: custom-naming-strategy.rst
.. _Write custom provider: custom-provider.rst
.. _Write custom cdn: custom-cdn.rst
.. _Write custom events adapter: custom-event-adapter.rst
.. _Write custom persistence adapter: custom-persistence-adapter.rst

