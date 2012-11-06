<?php

namespace Oryzone\Bundle\MediaStorageBundle\Exception;

class InvalidArgumentException extends MediaStorageException
{
    public function __construct($message = "", $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
