------------------
MediaStorageBundle
------------------

MediaStorage Bundle is a Symfony2 bundle that aims to provide a solid, extendible infrastructure to handle media storage
and retrieval. It is freely inspired to some famouse Symfony2 media management bundles such as `SonataMediaBundle`_ and
`AnoMediaBundle`_ but it wants to be more extendible and configurable.


**WARNING:** This bundle is going to be totally rewritten, please check the `master branch`_ for the last working version.

I will start by writing the README, following the `readme driven development`_ by `Tom Preston-Werner`_ , so everything you'll read heare is still the "design phase".
Submit a pull request if you want to share some ideas.

thanks to `@ellis-`_ for the support!


Requirements (basic concepts)
=============================

* **Medias** are used to represent a file (and connected informations) stored somewhere (filesystem, amazon S3, cdn, etc.)
* Each media is an entity and can be connected (related) to other entities (e.g. a ``Avatar`` media entity can be connected to a ``User`` entity)
* Media entity should be abstract and should work both in ODM and ORM contexts
* Each media entity should have an array that holds metadata (width, height, source, gps coords, author, version, etc...)
* Each media has a given type (image, video, text document, etc...)
* Each media type is managed by a **Provider**.
* Each media may have variants (e.g. default, big, small, hi-res, etc...)
* Each media can optionally have a thumbnail image
* Media files can be references to external files/resources (youtube/vimeo/scribd/slideshare/etc...)
* Media is stored to a given storage and located through a CDN configuration
* Media entites can be rendered in templates. Render method must print out appropriate html tags to display the content (``img``, ``video``, ``embed``, etc...)
* **Contexts** are used to define specific different media configurations (avatars, user pictures, etc...)
* Each context can defines processors (e.g. resizers) to convert original file to various media variants
* Processors can work **instantly** (when the media is created), **on-demand** (the first time a media variant is requested), **deferred** (pushed in a queue and processed asynchronously)
* Provide validators (formats, size, dimensions, proportions, etc) and form types (read, create, edit)
* Possibility to create named collection of medias (galleries)
* Has a data collector to show stats about stored/retrieved medias

Default available media types (and providers)
=============================================

* File
* Image
* Youtube
* Vimeo

Default available processors
============================

* ImageResizer
* Preserve

Configuration
=============

Here's a sample configuration

.. code-block:: yaml

  oryzone_media_storage:
      db_driver: doctrine_orm
          class:
              media:                Oryzone\Bundle\MediaStorageBundle\Entity\Media
              gallery:              Oryzone\Bundle\MediaStorageBundle\Entity\Gallery
              gallery_has_media:    Oryzone\Bundle\MediaStorageBundle\Entity\GalleryHasMedia
      providers:
          file:     oryzone_media_storage.providers.file
          image:    oryzone_media_storage.providers.image
          youtube:  oryzone_media_storage.providers.youtube
          vimeo:    oryzone_media_storage.providers.vimeo
      storages:
          avatars:
                local: { directory: %kernel.root_dir%/../web/images/pictures}
          product_pictures:
                S3: { bucket: 'productpics', key: '...'}
      cdn:
          avatars:
                local: { path: 'images/pictures/' }
          products_pictures:
                remote: { base_url: 'http://productpics.s3.amazonaws.com/' }
      contexts:
          avatar:
              provider: image
              thumbnail: false
              storage: pictures
              cdn: pictures
              variants:
                  square:
                      processor:
                          ImageResizer: { width: 50, height: 50, resizeMode: crop, format: jpg, quality: 90 }
                      mode: instantly
                  small:
                      processor:
                          ImageResizer: { width: 100, resizeMode: proportional, format: jpg, quality: 60 }
                      mode: instantly
                  medium:
                      processor:
                          ImageResizer: { width: 300, resizeMode: proportional, format: jpg, quality: 60 }
                      mode: instantly
                  large:
                      processor:
                          ImageResizer: { width: 800, resizeMode: proportional, format: jpg, quality: 70 }
                      mode: instantly
                  original:
                      processor: 
                          Preserve: ~
                      mode: instantly
              storages: ~ #TO DEFINE
          product_image:
              provider: image
              thumbnail: ~
              variants: ~
              storages: ~ #TO DEFINE


Interfaces/Objects
=================

Media (entity)
--------------

* id
* name
* content (not persisted)
* type
* context
* metadata (arbitrary array)
* variants (arbitrary array)
* createdAt
* modifiedAt


Gallery (entity)
----------------

* id
* name
* medias
* createdAt
* modifiedAt


GalleryHasMedia (entity)
------------------------

* id_media
* id_gallery
* order
* createdAt
* modifiedAt


ContextInterface
----------------

* getName()
* getProvider()
* getVariants()
* getThumbnailGenerator()
* ...


VariantInterface
----------------

* getName()
* getProcessor()
* getProcessorOptions()
* getMode()
* ...


ProviderInterface
--------

* getName()
* getRenderAvailableOptions()
* ...


ProcessInterface
----------------

* const STATUS_OK          = 1;
* const STATUS_SENDING     = 2;
* const STATUS_PENDING     = 3;
* const STATUS_ERROR       = 4;
* const STATUS_ENCODING    = 5;


ProcessorInterface
------------------

* process($binaryData, $context, $variant, $options)
* getAvailableOptions()
* ...


ThumbnailGeneratorInterface
---------------------------

* ...



Create a new Media
==================

Given ``Avatar`` a subclass of the ``Media`` entity and ``$user`` an instance of the ``User`` class:

.. code-block:: php

  $path = 'path/to/file.jpg';

  $avatar = new Avatar();
  $avatar->setName('Super Mario\'s profile picture');
  $avatar->setContent($path);

  $user->setAvatar( $avatar );

  $em = $this->getDoctrine()->getEntityManager();
  $em->persist($user);
  $em->flush();

Get a Media
===========

TODO!


Delete a Media
==============

TODO!


.. _SonataMediaBundle: https://github.com/sonata-project/SonataMediaBundle

.. _AnoMediaBundle: https://github.com/benjamindulau/AnoMediaBundle

.. _master branch: https://github.com/Oryzone/OryzoneMediaStorageBundle

.. _readme driven development: http://tom.preston-werner.com/2010/08/23/readme-driven-development.html

.. _Tom Preston-Werner: https://github.com/mojombo

.. _@ellis-: https://github.com/ellis-