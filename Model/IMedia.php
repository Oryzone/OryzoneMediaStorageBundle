<?php

namespace Oryzone\Bundle\MediaStorageBundle\Model;

/**
 * Interface that must be implemented by all the entities that refers to one media
 */
interface IMedia
{
    /**
     * @abstract
     * @return int|string get the id of the entity
     */
	public function getMediaId();

    /**
     * @abstract
     * @return string get the name of the connected media
     */
	public function getMediaName();

    /**
     * @abstract
     * @return string get the name of the media type
     */
	public function getMediaType();

    /**
     * @abstract
     * @return boolean TRUE if the media comes from an external service,
     * FALSE otherwise. If the media is external <getMediaName()> should
     * always return the full external url to the media.
     */
    public function isMediaExternal();
}