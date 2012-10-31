MediaStorageBundle
------------------

**WARNING:** This bundle is going to be totally rewritten, please check the `master branch`_ for the last working version.

I will start by rewriting the README first, following the `readme driven development`_ by `Tom Preston-Werner`_ , so everything you'll read heare is still the "design phase".
Submit a pull request if you have some ideas.

thanks to `@ellis-`_ for the support!


Requirements (basic concepts)
=============================

* Media are used to represent a file (and connected informations) stored somewhere (filesystem, amazon S3, cdn, etc.)
* Each media is an entity and can be connected (related) to other entities
* Media entity should be abstract and should work both in ODM and ORM contexts
* Each media entity should have an array to holds metadata (width, height, source, gps coords, author, version, etc...)
* Each media must have a type (image, video, text document, etc...)
* Each media may have variants (default, big, small, thumbnail, hi-res, etc...)
* Media files can be references to external files/resources (youtube/vimeo/scribd/slideshare/etc...)
* Media can be replicated to different storages
* Each Media must track every connected storage and it must be possible to retrieve the url of every stored file. E.g. to easily adopt S3/CDNs on production and local file system on development
* Each media type should have a dedicated Manager (to store and retrieve the entities)
* Managers can use processors (e.g. resizers) to convert original file to various required media variants
* Media entites can be rendered in templates. Render method must print out appropriate html tags to display the content (img, video, embed, etc...)
* Has validators and form types

Configuration
=============

Here's a sample configuration

.. code-block:: yaml

  oryzone_media_storage:
      types:
          image:
              entity:
                  class: ~
              processors:
                  resizer:
                      formats:
                          - { name: thumbnail, width: 50, height: 50, resizeMode: crop, quality: 90 }
                          - { name: small, width: 100, resizeMode: proportional, quality: 60 }
                          - { name: medium, width: 300, resizeMode: proportional, quality: 60 }
                          - { name: large, width: 800, resizeMode: proportional, quality: 70 }
              storages:
                  


Create a new Media
==================
From a controller

.. code-block:: php

  $path = 'path/to/file.jpg';

  $managerFactory = $this->get('media-manager-factory');
  $manager = $managerFactory->get('avatar');
  $avatarMedia = $manager->create($path);

Get a Media
===========

.. code-block:: php

  $managerFactory = $this->get('media-manager-factory');
  $manager = $managerFactory->get('avatar');
  $avatarMedia = $manager->findOneById(285);


Delete a Media
==============

TODO!




.. _master branch: https://github.com/Oryzone/OryzoneMediaStorageBundle

.. _readme driven development: http://tom.preston-werner.com/2010/08/23/readme-driven-development.html

.. _Tom Preston-Werner: https://github.com/mojombo

.. _@ellis-: https://github.com/ellis-