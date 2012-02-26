<?php

namespace Oryzone\Bundle\MediaStorageBundle\Service\Exception;

/**
 * Exception raised when a media file cannot be stored
 * @author Luciano Mammino
 */
class CannotStoreMediaException extends MediaStorageServiceException
{
    public function __construct($message = "", $name = null, $type = null, $size = null)
    {
        parent::__construct($message, $name, $type, $size);
    }
}