<?php

namespace Oryzone\Bundle\MediaStorageBundle\Service;

use Oryzone\Bundle\MediaStorageBundle\Model\MediaInterface;

/**
 * Abstract class used to simplify the creation of new media storage classes
 */
abstract class MediaStorage implements MediaStorageInterface
{

	/**
	 * {@inheritDoc}
	 */
	public function locateMedia(MediaInterface $media, $variant = NULL, $fallbackToDefaultVariant = true, $options = array())
	{
		return $this->locate($media->getMediaId(), $media->getMediaName(), $media->getMediaType(), $variant, $fallbackToDefaultVariant, $options);
	}

	/**
	 * {@inheritDoc}
	 */
	public function storeMedia($sourceFile, MediaInterface $media, $variant = NULL, $options = array())
	{
		$this->store($sourceFile, $media->getMediaId(), $media->getMediaName(), $media->getMediaType(), $variant, $options);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getStorageStateId()
	{
		return '';
	}

    /**
     * {@inheritDoc}
     */
    public function getSettings()
    {
        return array();
    }


}
