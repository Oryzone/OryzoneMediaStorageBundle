<?php

namespace Oryzone\Bundle\MediaStorageBundle\DependencyInjection;

use Oryzone\Bundle\MediaStorageBundle\Service\IMediaStorage;

/**
 * Interface that should be implemented by all the classes that need to know whats the current media storage instance
 * (Injection of the current media storage is up to the developers)
 */
abstract class MediaStorageAware implements MediaStorageAwareInterface
{
    /**
     * @var IMediaStorage the current media storage instance
     */
    protected $mediaStorage;

    /**
     * Inject the current media storage
     * @param IMediaStorage $mediaStorage
     */
    public function setMediaStorage(IMediaStorage $mediaStorage)
    {
        $this->mediaStorage = $mediaStorage;
    }

}