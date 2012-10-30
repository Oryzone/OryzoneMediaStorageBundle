MediaStorageBundle
------------------

**WARNING:** This bundle is going to be totally rewritten, please check the `master branch`_ for the last working version.

I will start by rewriting the README first, following the `readme driven development`_ by Tom Preston-Werner


Requirements
============

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



.. _master branch: https://github.com/Oryzone/OryzoneMediaStorageBundle

.. _readme driven development : http://tom.preston-werner.com/2010/08/23/readme-driven-development.html