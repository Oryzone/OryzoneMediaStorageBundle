<?php

namespace Oryzone\Bundle\MediaStorageBundle\Event;

use Symfony\Component\EventDispatcher\Event;

use Oryzone\MediaStorage\Model\MediaInterface;

class MediaEvent extends Event
{

    /**
     * @var \Oryzone\MediaStorage\Model\MediaInterface $media
     */
    protected $media;

    /**
     * Constructor
     *
     * @param \Oryzone\MediaStorage\Model\MediaInterface $media
     */
    public function __construct(MediaInterface $media)
    {
        $this->media = $media;
    }

    /**
     * Get media
     *
     * @return \Oryzone\MediaStorage\Model\MediaInterface
     */
    public function getMedia()
    {
        return $this->media;
    }

}
