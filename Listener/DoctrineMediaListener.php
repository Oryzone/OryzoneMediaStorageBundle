<?php

namespace Oryzone\Bundle\MediaStorageBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs,
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
     * @param  \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     * @return bool
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $object = $this->adapter->getObjectFromArgs($eventArgs);
        if($object instanceof Media)
        {
            //TODO put custom event here
            $this->mediaStorage->prepareMedia($object);
            $this->mediaStorage->saveMedia($object);
            //TODO put custom event here
        }
    }

    /**
     * @param  \Doctrine\ORM\Event\PreUpdateEventArgs $eventArgs
     * @return bool
     */
    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $object = $this->adapter->getObjectFromArgs($eventArgs);
        if ($object instanceof Media) {
            //TODO put custom event here
            $this->mediaStorage->prepareMedia($object, TRUE);
            $this->mediaStorage->updateMedia($object);
            $this->adapter->recomputeChangeSet($eventArgs);
            //TODO put custom event here
        }
    }

    /**
     * @param  \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     * @return bool
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $object = $this->adapter->getObjectFromArgs($eventArgs);
        if ($object instanceof Media)
        {
            //TODO put custom event here
            $this->mediaStorage->removeMedia($object);
            //TODO put custom event here
        }
    }

}
