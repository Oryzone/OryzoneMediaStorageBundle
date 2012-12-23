<?php

namespace Oryzone\Bundle\MediaStorageBundle\Listener;

use Doctrine\Common\EventArgs,
    Doctrine\ORM\Event\PreUpdateEventArgs;

use Oryzone\Bundle\MediaStorageBundle\MediaStorageInterface,
    Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Listener\Adapter\AdapterInterface;

class DoctrineMediaListener
{

    /**
     * @var \Oryzone\Bundle\MediaStorageBundle\MediaStorageInterface $mediaStorage
     */
    protected $mediaStorage;

    /**
     * @var Adapter\AdapterInterface $adapter
     */
    protected $adapter;

    /**
     * Constructor
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\MediaStorageInterface             $mediaStorage
     * @param \Oryzone\Bundle\MediaStorageBundle\Listener\Adapter\AdapterInterface $adapter
     */
    public function __construct(MediaStorageInterface $mediaStorage, AdapterInterface $adapter)
    {
        $this->mediaStorage = $mediaStorage;
        $this->adapter = $adapter;
    }

    /**
     * @param \Doctrine\Common\EventArgs $eventArgs
     * @return bool
     */
    public function prePersist(EventArgs $eventArgs)
    {
        $object = $this->adapter->getObjectFromArgs($eventArgs);
        if($object instanceof Media)
        {
            $this->mediaStorage->prepareMedia($object);
            $this->mediaStorage->saveMedia($object);
        }
    }

    /**
     * @param \Doctrine\Common\EventArgs $eventArgs
     * @return bool
     */
    public function preUpdate(EventArgs $eventArgs)
    {
        $object = $this->adapter->getObjectFromArgs($eventArgs);
        if ($object instanceof Media) {
            $this->mediaStorage->prepareMedia($object, TRUE);
            $this->mediaStorage->updateMedia($object);
            $this->adapter->recomputeChangeSet($eventArgs);
        }
    }

    /**
     * @param \Doctrine\Common\EventArgs $eventArgs
     * @return bool
     */
    public function preRemove(EventArgs $eventArgs)
    {
        $object = $this->adapter->getObjectFromArgs($eventArgs);
        if ($object instanceof Media)
        {
            $this->mediaStorage->removeMedia($object);
        }
    }

}
