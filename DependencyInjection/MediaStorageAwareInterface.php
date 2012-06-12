<?php

namespace Oryzone\Bundle\MediaStorageBundle\DependencyInjection;

use Oryzone\Bundle\MediaStorageBundle\Service\MediaStorageInterface;

/**
 * Interface that should be implemented by all the classes that need to know whats the current media storage instance
 * (Injection of the current media storage is up to the developers)
 */
interface MediaStorageAwareInterface
{
    /**
     * Inject the current media storage
     * @abstract
     * @param \Oryzone\Bundle\MediaStorageBundle\Service\MediaStorageInterface $mediaStorage
     */
    public function setMediaStorage(MediaStorageInterface $mediaStorage);
}