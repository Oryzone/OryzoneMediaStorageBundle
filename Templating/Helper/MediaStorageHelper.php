<?php

namespace Oryzone\Bundle\MediaStorageBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;

use Oryzone\MediaStorage\MediaStorageInterface,
    Oryzone\MediaStorage\Model\MediaInterface;

class MediaStorageHelper extends Helper implements MediaStorageHelperInterface
{

    protected $mediaStorage;

    public function __construct(MediaStorageInterface $mediaStorage)
    {
        $this->mediaStorage = $mediaStorage;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'oryzone_media_storage_helper';
    }

    /**
     * {@inheritDoc}
     */
    public function url(MediaInterface $media, $variant = NULL, $options = array())
    {
        return $this->mediaStorage->getUrl($media, $variant, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function render(MediaInterface $media, $variant = NULL, $options = array())
    {
        return $this->mediaStorage->render($media, $variant, $options);
    }

}
