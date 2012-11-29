<?php

namespace Oryzone\Bundle\MediaStorageBundle\Event;

use Symfony\Component\EventDispatcher\Event;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\MediaStorage;

class MediaEvent extends Event
{

    /**
     * @var \Oryzone\Bundle\MediaStorageBundle\Model\Media $media
     */
    protected $media;

    /**
     * @var \Oryzone\Bundle\MediaStorageBundle\MediaStorage $mediaStorage
     */
    protected $mediaStorage;

    /**
     * Constructor
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media $media
     * @param \Oryzone\Bundle\MediaStorageBundle\MediaStorage $mediaStorage
     */
    public function __construct(Media $media, MediaStorage $mediaStorage)
    {
        $this->media = $media;
        $this->mediaStorage = $mediaStorage;
    }

    /**
     * Get media
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\Model\Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Get media storage
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\MediaStorage
     */
    public function getMediaStorage()
    {
        return $this->mediaStorage;
    }

}
