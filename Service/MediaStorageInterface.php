<?php

namespace Oryzone\Bundle\MediaStorageBundle\Service;

use Oryzone\Bundle\MediaStorageBundle\Service\Exception\CannotLocateMediaException;
use Oryzone\Bundle\MediaStorageBundle\Service\Exception\CannotStoreMediaException;
use Oryzone\Bundle\MediaStorageBundle\Model\MediaInterface;

/**
 * Interface that must be implemented by every media storage strategy
 * @author Luciano Mammino
 */
Interface MediaStorageInterface
{

	/**
	 * Locates a stored media
	 * @abstract
	 * @param int|string    $id             the id of the related entity
	 * @param string        $name           the slug name of the media
	 * @param string        $type           the type of the image
	 * @param string        $variant        a tag that identifies the size or a generic variant of the image
	 *                                      (eg. "small", "big", "uncompressed", "hd")
	 * @param bool          $fallbackToDefaultVariant       flag used to determinate whether to fallback to
	 *                                                       original variant if the given variant where not found
	 * @return string       the path/url of the media
	 */
    public function locate($id, $name, $type, $variant = NULL, $fallbackToDefaultVariant = true);

	/**
	 * Locates a stored media by using a Media entity
	 * @abstract
	 * @param   MediaInterface  $media the media to locate
	 * @param   null            $variant a tag that identifies the size or a generic variant of the image
	 *                           (eg. "small", "big", "uncompressed", "hd")
	 * @param   bool            $fallbackToDefaultVariant   flag used to determinate whether to fallback to
	 *                                              original variant if the given variant where not found
	 * @return  string  the path/url of the media
	 */
	public function locateMedia(MediaInterface $media, $variant = NULL, $fallbackToDefaultVariant = true);
    
    /**
     * Store a media
     * @abstract
     * @param string 		$sourceFile 	the path of the file to store
     * @param int|string    $id 			the id of the related entity
     * @param string 		$name 			the slug name of the image
     * @param string 		$type 			the type of the image
     * @param string 		$variant 		a tag that identifies the size or a generic variant of the image
     * @throws CannotStoreMediaException if cannot store the image
     */
    public function store($sourceFile, $id, $name, $type, $variant = NULL);

	/**
	 * Store a media by using a Media entity
	 * @abstract
	 * @param string            $sourceFile the path of the file to store
	 * @param MediaInterface    $media
	 * @param string 		    $variant 		a tag that identifies the size or a generic variant of the image
	 * @throws CannotStoreMediaException if cannot store the image
	 */
	public function storeMedia($sourceFile, MediaInterface $media, $variant = NULL);

	/**
	 * Returns a string that identifies all the currently set media storage settings. Mostly used for cache purposes
	 * @abstract
	 * @return string
	 */
	public function getStorageStateId();
}