<?php

namespace Oryzone\Bundle\MediaStorageBundle\Templating\Helper;

/*
 * This file is part of the Oryzone/MediaStorage package.
 *
 * (c) Luciano Mammino <lmammino@oryzone.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code (Resources/meta/LICENSE).
 */

use Symfony\Component\Templating\Helper\Helper;

use Oryzone\MediaStorage\MediaStorageInterface,
    Oryzone\MediaStorage\Model\MediaInterface;

class MediaStorageHelper extends Helper implements MediaStorageHelperInterface
{

    /**
     * @var \Oryzone\MediaStorage\MediaStorageInterface $mediaStorage
     */
    protected $mediaStorage;

    /**
     * Constructor
     *
     * @param \Oryzone\MediaStorage\MediaStorageInterface $mediaStorage
     */
    public function __construct(MediaStorageInterface $mediaStorage)
    {
        $this->mediaStorage = $mediaStorage;
    }

    /**
     * {@inheritDoc}
     */
    public function getMediaStorage()
    {
        return $this->mediaStorage;
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