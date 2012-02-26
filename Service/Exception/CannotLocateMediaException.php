<?php

namespace Oryzone\Bundle\MediaStorageBundle\Service\Exception;

/**
 * Exception raised when a media file cannot be located
 * @author Luciano Mammino
 */
class CannotLocateMediaException extends MediaStorageServiceException
{
    public function __construct($message = "", $name = null, $type = null, $size = null)
    {
        parent::__construct($message, $name, $type, $size);
    }
}