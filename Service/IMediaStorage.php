<?php

namespace Oryzone\Bundle\MediaStorageBundle\Service;

use Oryzone\Bundle\MediaStorageBundle\Service\CannotLocateMediaException;
use Oryzone\Bundle\MediaStorageBundle\Service\CannotStoreMediaException;

/**
 * Interface that must be implemented by every media storage strategy
 * @author Luciano Mammino
 */
Interface IMediaStorage
{
    
    /**
     * Locates a stored image
     * @param int|string	$id 		the id of the related entity
     * @param string		$name 		the slug name of the media
     * @param string		$type 		the type of the image
     * @param string		$variant 	a tag that identifies the size or a generic variant of the image (eg. "small", "big", "uncompressed", "hd")
     * @throws CannotLocateMediaException if cannot locate the image
     */
    public function locate($id, $name, $type, $variant = null);
    
    /**
     * Locates a stored image
     * @param string 		$sourceFile 	the path of the file to store
     * @param int|string    $id 			the id of the related entity
     * @param string 		$name 			the slug name of the image
     * @param string 		$type 			the type of the image
     * @param string 		$variant 		a tag that identifies the size or a generic variant of the image
     * @throws CannotStoreMediaException if cannot store the image
     */
    public function store($sourceFile, $id, $name, $type, $variant = null);
}