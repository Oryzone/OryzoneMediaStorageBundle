<?php

namespace Oryzone\Bundle\MediaStorageBundle\Exception\Integration;

use Oryzone\Bundle\MediaStorageBundle\Exception\MediaStorageException;

class ResourceNotFoundException extends MediaStorageException
{

    /**
     * A unique identifier for the resource
     *
     * @var string $id
     */
    protected $id;

    /**
     * @param string $message {@inheritDoc}
     * @param string $id A unique identifier for the resource
     * @param int $code {@inheritDoc}
     * @param \Exception $previous {@inheritDoc}
     */
    public function __construct($message = "", $id, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->id = $id;
    }

    /**
     * Gets the id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

}
