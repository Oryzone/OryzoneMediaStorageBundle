<?php

namespace Oryzone\Bundle\MediaStorageBundle\Listener\Adapter;

use Doctrine\Common\EventArgs;

/**
 * Adapter interface to build adapters for the doctrine media listener
 */
interface AdapterInterface
{

    /**
     * Gets the mapped object from the event arguments.
     *
     * @param \Doctrine\Common\EventArgs $e The event arguments.
     *
     * @return object The mapped object.
     */
    public function getObjectFromArgs(EventArgs $e);

    /**
     * Recomputes the change set for the object.
     *
     * @param EventArgs $e The event arguments.
     */
    public function recomputeChangeSet(EventArgs $e);

}
