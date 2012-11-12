<?php

namespace Oryzone\Bundle\MediaStorageBundle\Listener\Adapter\ODM\MongoDB;

use Doctrine\Common\EventArgs,
    Doctrine\ORM\Proxy\Proxy;

use Oryzone\Bundle\MediaStorageBundle\Listener\Adapter\AdapterInterface;

class MongoDBAdapter implements AdapterInterface
{
    /**
     * {@inheritDoc}
     */
    public function getObjectFromArgs(EventArgs $e)
    {
        return $e->getDocument();
    }

    /**
     * {@inheritDoc}
     */
    public function recomputeChangeSet(EventArgs $e)
    {
        $obj = $this->getObjectFromArgs($e);

        $manager = $this->getManagerFromArgs($e);
        $uow = $manager->getUnitOfWork();
        $metadata = $manager->getClassMetadata(get_class($obj));
        $uow->recomputeSingleDocumentChangeSet($metadata, $obj);
    }

    /**
     * {@inheritDoc}
     */
    public function getManagerFromArgs(EventArgs $e)
    {
        return $e->getDocumentManager();
    }
}
