<?php

namespace Oryzone\Bundle\MediaStorageBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs,
    Doctrine\ORM\Event\PreUpdateEventArgs;

use Oryzone\Bundle\MediaStorageBundle\MediaStorageInterface,
    Oryzone\Bundle\MediaStorageBundle\Model\Media;

class DoctrineMediaListener
{

    /**
     * @var \Oryzone\Bundle\MediaStorageBundle\MediaStorageInterface $mediastorage
     */
    protected $mediaStorage;

    /**
     * Constructor
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\MediaStorageInterface $mediaStorage
     */
    public function __construct(MediaStorageInterface $mediaStorage)
    {
        $this->mediaStorage = $mediaStorage;
    }


    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     * @return bool
     */
    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        if(!$entity instanceof Media)
            return false;

        $this->mediaStorage->prepareMedia($entity);
        return true;
    }


    /**
     * @param \Doctrine\ORM\Event\PreUpdateEventArgs $eventArgs
     * @return bool
     */
    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        if($entity instanceof Media)
        {
            $this->mediaStorage->prepareMedia($entity);

            // Hack ? Don't know, that's the behaviour Doctrine 2 seems to want
            // See : http://www.doctrine-project.org/jira/browse/DDC-1020
            $em = $eventArgs->getEntityManager();
            $uow = $em->getUnitOfWork();
            $uow->recomputeSingleEntityChangeSet
            (
                $em->getClassMetadata(get_class($entity)),
                $eventArgs->getEntity()
            );
            return true;
        }
        return false;
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     * @return bool
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        if (!$entity instanceof Media)
            return false;

        $this->mediaStorage->saveMedia($entity);
        return true;
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     * @return bool
     */
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        if (!$entity instanceof Media)
            return false;

        $this->mediaStorage->updateMedia($entity);
        return true;
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     * @return bool
     */
    public function preRemove(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        if (!$entity instanceof Media)
            return false;

        $this->mediaStorage->removeMedia($entity);
        return true;
    }

}
