------------------
MediaStorageBundle
------------------

MediaStorageBundle is a `Symfony2`_ bundle that aims to provide a solid, extendible infrastructure to handle media storage
and retrieval. It's built for `Doctrine2`_ and allows to store files (simple files, images, videos, documents) and related
metadata as Doctrine entities (Doctrine ORM) or documents (Doctrine ODM). It internally adopt `Gaufrette`_ to abstract the
filesystem and allows to store files on many external filesystem like `Amazon`_ and `Rackspace`_ or through FTP or SFTP.
It is freely inspired to some famouse Symfony2 media management bundles such as `SonataMediaBundle`_,
`AnoMediaBundle`_ and `VichUploaderBundle`_ but it introduces several different peculiarities and tries to be more
extensible and configurable.

**WARNING:** This bundle version (2.0) is still experimental. It's heavily untested and undocumented, so please do not
use it in production. If you prefer a more stable release checkout the 1.0 version at the `master branch`_.

Providers
---------
MediaStorageBundle is based on the concept of **provider**. A provider is a way to abstract the logic behind a certain
type of media. The bundle offers a series of default providers to store the following media types (and their metadata):

* Files
* Images (that can be automatically stretched, shrunk, cropped and converted)
* Youtube and Vimeo videos (preview image is locally downloaded and che be elaborated as an image)

So if your environment is built on top of Symfony2/Doctrine2 need to work with external files, images, and online videos
(Youtube/Vimeo) the bundle should offer everything you need to get the job done. If you need some custom logic (e.g.
video conversion, pdf preview generation, etc.) you can easily implement your custom provider.

Installation
------------
As every Symfony 2.1 bundle, MediaStorageBundle can be installed through `Composer`_. Just add the following dependency
inside the ``require`` block of your ``composer.json`` file:

.. code-block:: javascript

  "oryzone/media-storage-bundle": "2.0.x-dev"

So tell composer to download and install the new bundle:

.. code-block:: bash

  php composer.phar update oryzone/media-storage-bundle

Then you must enable the bundle in your ``AppKernel.php`` file by adding the following line in your ``$bundles`` array:

.. code-block:: php

  new \Oryzone\Bundle\MediaStorageBundle\OryzoneMediaStorageBundle(),

Configuration
-------------
Refer to the `documentation`_

Dependencies
------------
* `GaufretteBundle`_ (required, automatically installed by `Composer`_)
* `ImagineBundle`_ (optional, needed if you want to use Image, Vimeo and Youtube providers)
* `BuzzBundle`_ (optional, needed if you want to use Youtube and Vimeo providers)

To install the optional dependencies please refer to the official documentation of each bundle.

Documentation
-------------
Full documentation (still incomplete) is available here: `/Resources/doc/index.rst`_

Contribution
------------
**Contributions are always welcome!**
If you need to report a bug you can use the `github issues for the repository`_ (please specify that it's referred to the
version 2 of the bundle).
Otherwise you can easily introduce new features, improvements or fixes by `forking the repository`_
and submitting a pull request.
This list provides the next thing Oryzone would like to implement in the bundle. So if you want to contribute and don't
know where to begin here's some hints ;)

* PhpUnit tests
* Travis CI integration
* Improve documentation
* Build providers for famous external services like DailyMotion, Twitter, SlideShare, etc...
* Integrate different ways of processing media (on demand and background)

Thanks

.. _Symfony2: http://symfony.com/

.. _Doctrine2: http://www.doctrine-project.org/

.. _Amazon: http://aws.amazon.com/

.. _Rackspace: http://www.rackspace.com/

.. _SonataMediaBundle: https://github.com/sonata-project/SonataMediaBundle

.. _AnoMediaBundle: https://github.com/benjamindulau/AnoMediaBundle

.. _VichUploaderBundle: https://github.com/dustin10/VichUploaderBundle

.. _master branch: https://github.com/Oryzone/OryzoneMediaStorageBundle

.. _Composer: http://getcomposer.org/

.. _Gaufrette: https://github.com/KnpLabs/Gaufrette

.. _GaufretteBundle: https://github.com/KnpLabs/KnpGaufretteBundle

.. _ImagineBundle: https://github.com/avalanche123/AvalancheImagineBundle

.. _BuzzBundle: https://github.com/sensio/SensioBuzzBundle

.. _/Resources/doc/index.rst: https://github.com/Oryzone/OryzoneMediaStorageBundle/blob/2.0/Resources/doc/index.rst

.. _documentation: https://github.com/Oryzone/OryzoneMediaStorageBundle/blob/2.0/Resources/doc/index.rst

.. _github issues for the repository: https://github.com/Oryzone/OryzoneMediaStorageBundle/issues

.. _forking the repository: https://github.com/Oryzone/OryzoneMediaStorageBundle/fork_select