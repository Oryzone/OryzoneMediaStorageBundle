<?php

namespace Oryzone\Bundle\MediaStorageBundle\Service;

use Oryzone\Bundle\MediaStorageBundle\Entity\IMedia;

/**
 * Abstract class used to simplify the creation of new media storage classes
 */
abstract class AbstractMediaStorage implements IMediaStorage
{

	/**
	 * {@inheritDoc}
	 */
	public function locateMedia(IMedia $media, $variant = NULL, $fallbackToDefaultVariant = true)
	{
		return $this->locate($media->getMediaId(), $media->getMediaName(), $media->getMediaType(), $variant, $fallbackToDefaultVariant);
	}

	/**
	 * {@inheritDoc}
	 */
	public function storeMedia($sourceFile, IMedia $media, $variant = NULL)
	{
		$this->store($sourceFile, $media->getMediaId(), $media->getMediaName(), $media->getMediaType(), $variant);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getStorageStateId()
	{
		return '';
	}


}
