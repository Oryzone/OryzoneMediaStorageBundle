<?php

namespace Oryzone\Bundle\MediaStorageBundle\Event;

/*
 * This file is part of the Oryzone/MediaStorage package.
 *
 * (c) Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (Resources/meta/LICENSE).
 */

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Oryzone\MediaStorage\Event\EventDispatcherAdapterInterface,
    Oryzone\MediaStorage\Model\MediaInterface;

class SymfonyEventDispatcherAdapter implements EventDispatcherAdapterInterface
{
    /**
     * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    protected $eventDispatcher;

    /**
     * Constructor
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     */
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritDoc}
     */
    public function onBeforeProcess(MediaInterface $media)
    {
        $mediaEvent = new MediaEvent($media);
        $this->eventDispatcher->dispatch(MediaEvents::BEFORE_PROCESS, $mediaEvent);
    }

    /**
     * {@inheritDoc}
     */
    public function onAfterProcess(MediaInterface $media)
    {
        $mediaEvent = new MediaEvent($media);
         $this->eventDispatcher->dispatch(MediaEvents::AFTER_PROCESS, $mediaEvent);
    }

    /**
     * {@inheritDoc}
     */
    public function onBeforeStore(MediaInterface $media)
    {
        $mediaEvent = new MediaEvent($media);
        $this->eventDispatcher->dispatch(MediaEvents::BEFORE_STORE, $mediaEvent);
    }

    /**
     * {@inheritDoc}
     */
    public function onAfterStore(MediaInterface $media)
    {
        $mediaEvent = new MediaEvent($media);
        $this->eventDispatcher->dispatch(MediaEvents::AFTER_STORE, $mediaEvent);
    }

    /**
     * {@inheritDoc}
     */
    public function onBeforeUpdate(MediaInterface $media)
    {
        $mediaEvent = new MediaEvent($media);
        $this->eventDispatcher->dispatch(MediaEvents::BEFORE_UPDATE, $mediaEvent);
    }

    /**
     * {@inheritDoc}
     */
    public function onAfterUpdate(MediaInterface $media)
    {
        $mediaEvent = new MediaEvent($media);
        $this->eventDispatcher->dispatch(MediaEvents::AFTER_UPDATE, $mediaEvent);
    }

    /**
     * {@inheritDoc}
     */
    public function onBeforeRemove(MediaInterface $media)
    {
        $mediaEvent = new MediaEvent($media);
        $this->eventDispatcher->dispatch(MediaEvents::BEFORE_REMOVE, $mediaEvent);
    }

    /**
     * {@inheritDoc}
     */
    public function onAfterRemove(MediaInterface $media)
    {
        $mediaEvent = new MediaEvent($media);
        $this->eventDispatcher->dispatch(MediaEvents::AFTER_REMOVE, $mediaEvent);
    }

    /**
     * {@inheritDoc}
     */
    public function onBeforeModelPersist(MediaInterface $media, $update = FALSE)
    {
        $mediaEvent = new MediaEvent($media);
        $this->eventDispatcher->dispatch(MediaEvents::BEFORE_MODEL_PERSIST, $mediaEvent);
    }

    /**
     * {@inheritDoc}
     */
    public function onAfterModelPersist(MediaInterface $media, $update = FALSE)
    {
        $mediaEvent = new MediaEvent($media);
        $this->eventDispatcher->dispatch(MediaEvents::AFTER_MODEL_PERSIST, $mediaEvent);
    }

    /**
     * {@inheritDoc}
     */
    public function onBeforeModelRemove(MediaInterface $media)
    {
        $mediaEvent = new MediaEvent($media);
        $this->eventDispatcher->dispatch(MediaEvents::BEFORE_MODEL_REMOVE, $mediaEvent);
    }

    /**
     * {@inheritDoc}
     */
    public function onAfterModelRemove(MediaInterface $media)
    {
        $mediaEvent = new MediaEvent($media);
        $this->eventDispatcher->dispatch(MediaEvents::AFTER_MODEL_REMOVE, $mediaEvent);
    }
}
