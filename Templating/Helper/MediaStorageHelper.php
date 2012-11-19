<?php

namespace Oryzone\Bundle\MediaStorageBundle\Templating\Helper;

use Symfony\Component\Templating\Helper\Helper;

use Oryzone\Bundle\MediaStorageBundle\MediaStorageInterface,
    Oryzone\Bundle\MediaStorageBundle\Model\Media;

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
    public function path(Media $media, $variant = NULL, $options = array())
    {
        return $this->mediaStorage->getPath($media, $variant, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function url(Media $media, $variant = NULL, $options = array())
    {
        return $this->mediaStorage->getUrl($media, $variant, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function render(Media $media, $variant = NULL, $options = array())
    {
        return $this->mediaStorage->render($media, $variant, $options);
    }

}
