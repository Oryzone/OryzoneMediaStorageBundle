<?php

namespace Oryzone\Bundle\MediaStorageBundle\Model;

/**
 * Abstract class to simplify the creation of Media entities
 */
abstract class Media implements MediaInterface
{

	/**
	 * The id of the media
	 *
	 * @var string $mediaId
	 */
	protected $mediaId;

	/**
	 * The name of the media
	 *
	 * @var string $mediaName
	 */
	protected $mediaName;

	/**
	 * The type of the media
	 *
	 * @var string $mediaType
	 */
	protected $mediaType;

	/**
	 * A boolean flag used to indicate whether the current media refers to an external source
	 *
	 * @var bool $external
	 */
	protected $external = false;

	/**
	 * {@inheritDoc}
	 */
	public function getMediaId()
	{
		return $this->mediaId;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getMediaName()
	{
		return $this->mediaName;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getMediaType()
	{
		return $this->mediaType;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isMediaExternal()
	{
		return $this->external;
	}

	/**
	 * Set if media is external
	 *
	 * @param boolean $external
	 */
	public function setExternal($external)
	{
		$this->external = $external;
	}

	/**
	 * Set media id
	 *
	 * @param string $mediaId
	 */
	public function setMediaId($mediaId)
	{
		$this->mediaId = $mediaId;
	}

	/**
	 * Set media name
	 *
	 * @param string $mediaName
	 */
	public function setMediaName($mediaName)
	{
		$this->mediaName = $mediaName;
	}

	/**
	 * Set media type
	 *
	 * @param string $mediaType
	 */
	public function setMediaType($mediaType)
	{
		$this->mediaType = $mediaType;
	}
}
