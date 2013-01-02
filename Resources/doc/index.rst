--------------------------
Documentation to come soon
--------------------------
Please refer to the README.rst for the moment

Requirements (basic concepts)
=============================

* **Medias** are used to represent a file (and connected informations) stored somewhere (filesystem, amazon S3, cdn, etc.)
* Each media is an entity and can be connected (related) to other entities (e.g. a ``Avatar`` media entity can be connected to a ``User`` entity)
* Media entity should be abstract and should work both in ODM and ORM contexts
* Each media entity should have an array that holds metadata (width, height, source, gps coords, author, version, etc...)
* Each media has a given type (image, video, text document, etc...)
* Each media may have **variants** (e.g. default, big, small, hi-res, long, short, subtitled, censored, etc...)
* **Variants** are structured as a tree with a 'default' variant on the root and other variants as child.
* Each child in the variant tree is builded from the file resulting by its parent variant
* Each media type is managed by a **Provider**.
* Each provide defines a ``process`` method (e.g. used to resize or optimize pictures) to convert original file to various media **variants**
* **Process method** can be called **instantly** (when the media is created - ``instant`` mode), **on-demand** (the first time a media variant is requested - ``lazy`` mode), **deferred** (pushed in a queue and processed asynchronously - ``queue`` mode)
* Media files can be references to external files/resources (youtube/vimeo/scribd/slideshare/etc...)
* Media is stored to a given filesystem and located through a CDN configuration
* Media entites can be rendered in templates. Render method must print out appropriate html tags to display the content (``img``, ``video``, ``embed``, etc...)
* **Contexts** are used to define specific different media configurations (avatars, user pictures, etc...)
* Provide validators (formats, size, dimensions, proportions, etc) and form types (read, create, edit)
* Possibility to create named collection of medias (e.g. galleries)
* Has a data collector to show stats about stored/retrieved medias