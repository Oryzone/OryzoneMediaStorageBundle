<?php

namespace Oryzone\Bundle\MediaStorageBundle\Service;

use Oryzone\Bundle\MediaStorageBundle\Service\Exception\CannotLocateMediaException;
use Oryzone\Bundle\MediaStorageBundle\Service\Exception\CannotStoreMediaException;
use Oryzone\Bundle\MediaStorageBundle\Entity\IMedia;

/**
 * Interface that must be implemented by every media storage strategy
 * @author Luciano Mammino
 */
Interface IMediaStorage
{
    
    /**
     * Locates a stored media
     * @abstract
     * @param int|string	$id 		the id of the related entity
     * @param string		$name 		the slug name of the media
     * @param string		$type 		the type of the image
     * @param string		$variant 	a tag that identifies the size or a generic variant of the image (eg. "small", "big", "uncompressed", "hd")
     * @return string       the path/url of the media
     * @throws CannotLocateMediaException if cannot locate the image
     */
    public function locate($id, $name, $type, $variant = NULL);

	/**
	 * Locates a stored media by using a IMedia entity
	 * @abstract
	 * @param \Oryzone\Bundle\MediaStorageBundle\Entity\IMedia $media the media to locate
	 * @return string the path/url of the media
	 * @throws CannotLocateMediaException if cannot locate the image
	 */
	public function locateMedia(IMedia $media, $variant = NULL);
    
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
	 * Store a media by using a IMedia entity
	 * @abstract
	 * @param string $sourceFile the path of the file to store
	 * @param \Oryzone\Bundle\MediaStorageBundle\Entity\IMedia $media
	 * @param string 		$variant 		a tag that identifies the size or a generic variant of the image
	 * @throws CannotStoreMediaException if cannot store the image
	 */
	public function storeMedia($sourceFile, IMedia $media, $variant = NULL);
}