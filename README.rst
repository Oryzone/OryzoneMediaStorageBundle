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
* Each provide defines a ``process`` method (e.g. used to resize or optimize pictures) to convert original file to various media variants
* **Process method** can be called **instantly** (when the media is created - ``instant`` mode), **on-demand** (the first time a media variant is requested - ``lazy`` mode), **deferred** (pushed in a queue and processed asynchronously - ``queue`` mode)
* Each media may have variants (e.g. default, big, small, hi-res, long, short, subtitled, censored, etc...)
* Media files can be references to external files/resources (youtube/vimeo/scribd/slideshare/etc...)
* Media is stored to a given filesystem and located through a CDN configuration
* Media entites can be rendered in templates. Render method must print out appropriate html tags to display the content (``img``, ``video``, ``embed``, etc...)
* **Contexts** are used to define specific different media configurations (avatars, user pictures, etc...)
* Provide validators (formats, size, dimensions, proportions, etc) and form types (read, create, edit)
* Possibility to create named collection of medias (e.g. galleries)
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

Configuration
=============

Here's a sample configuration. `Gaufrette`_ and `GaufretteBundle`_ are required as they are used to abstract filesystems.

.. code-block:: yaml

  knp_gaufrette:
      adapters:
          avatars:
              local:
                  directory: '%kernel.root_dir%/../web/images/pictures'
          product_pictures:
              amazon:
                  bucket: 'productpics'
                  create: false

  oryzone_media_storage:
      db_driver: doctrine_orm
      namingStrategies:
          default:  oryzone_media_storage.namingStrategies.slugged
      providers:
          default:  oryzone_media_storage.providers.file
          image:    oryzone_media_storage.providers.image
          youtube:  oryzone_media_storage.providers.youtube
          vimeo:    oryzone_media_storage.providers.vimeo
      cdns:
          avatars:
                local: { path: 'images/pictures/' }
          products_pictures:
                remote: { base_url: 'http://productpics.s3.amazonaws.com/' }
      contexts:
          avatar:
              provider: image
              filesystem: pictures
              cdn: pictures
              variants:
                  default:
                      process: { width: 800, resizeMode: proportional, format: jpg, quality: 90, enlarge: false }
                      # must be processed instantaneously
                  square:
                      process: { width: 50, height: 50, mode: crop, format: jpg, quality: 90 }
                      mode: instant
                      from: default
                  small:
                      process: { width: 100, resizeMode: proportional, format: jpg, quality: 60 }
                      mode: instant
                      from: default
                  medium:
                      process: { width: 300, resizeMode: proportional, format: jpg, quality: 60 }
                      mode: instant
                      from: default
                  large:
                      process: { width: 500, resizeMode: proportional, format: jpg, quality: 70 }
                      mode: instant
                      from: default
          product_picture:
              provider: image
              filesystem: product_pictures
              cdn: product_pictures
              variants: ~


Prototyping
=================

MediaStorage
------------

* cdnFactory
* contextFactory
* filesystemMap (from gaufrette bundle)
* providerFactory
* prepareMedia(Media $media)
* saveMedia(Media $media)
* removeMedia(Media $media)
* getPath(Media $media)
* getUrl(Media $media)


Media (entity)
--------------

* id
* name
* content (not persisted)
* provider
* context
* metadata (arbitrary array)
* variants (arbitrary array)
* createdAt
* modifiedAt


MediaCollection (entity)
----------------

* id
* name
* medias
* createdAt
* modifiedAt


MediaCollectionHasMedia (entity)
------------------------

* media
* collection
* position
* createdAt
* modifiedAt


ContextInterface
----------------

* getName()
* getProviderName()
* getFilesystemName()
* getCdnName()
* getVariants()
* ...


VariantInterface
----------------
* const STATUS_READY       = 1;
* const STATUS_ON_DEMAND   = 2;
* const STATUS_QUEUED      = 3;
* const STATUS_PROCESSING  = 5;
* const STATUS_ERROR       = 4;
* getName()
* getFilename()
* getContentType()
* getOptions()
* getMode()
* getState()
* isReady() (checks if the state is READY)
* hasError() (checks if the state is ERROR)
* getError() (filled in case of the state ERROR)
* toArray()
* fromArray()

ProviderInterface
--------

* getName()
* getRenderAvailableOptions()
* supports(Media $media)
* render(Media $media, $variantName, $options = array(), CdnInterface $cdn = NULL)
* process(Media $media, VariantInterface $variant)
* ...



Create a new Media
==================

Given ``Avatar`` a subclass of the ``Media`` entity and ``$user`` an instance of the ``User`` class.
N.B. ``User`` class mapping with ``avatar`` should have set the option ``cascade=all``.

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

.. _Gaufrette: https://github.com/KnpLabs/Gaufrette

.. _GaufretteBundle: https://github.com/KnpLabs/KnpGaufretteBundle