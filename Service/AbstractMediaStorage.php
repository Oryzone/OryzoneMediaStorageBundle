<?php

namespace Oryzone\Bundle\MediaStorageBundle\Service;

use Oryzone\Bundle\MediaStorageBundle\Entity\IMedia;

/**
 * Abstract class used to simplify the creation of new media storage classes
 */
abstract class AbstractMediaStorage implements IMediaStorage
{

	/**
	 * Locates a stored media by using a IMedia entity
	 * @param \Oryzone\Bundle\MediaStorageBundle\Entity\IMedia $media the media to locate
	 * @return string the path/url of the media
	 * @throws CannotLocateMediaException if cannot locate the image
	 */
	public function locateMedia(IMedia $media, $variant = NULL)
	{
		return $this->locate($media->getMediaId(), $media->getMediaName(), $media->getMediaType(), $variant);
	}

	/**
	 * Store a media by using a IMedia entity
	 * @param string $sourceFile the path of the file to store
	 * @param \Oryzone\Bundle\MediaStorageBundle\Entity\IMedia $media
	 * @param string         $variant         a tag that identifies the size or a generic variant of the image
	 * @throws CannotStoreMediaException if cannot store the image
	 */
	public function storeMedia($sourceFile, IMedia $media, $variant = NULL)
	{
		$this->store($sourceFile, $media->getMediaId(), $media->getMediaName(), $media->getMediaType(), $variant);
	}
}
