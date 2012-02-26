<?php

namespace Oryzone\Bundle\MediaStorageBundle\Service\Exception;

/**
 * Base class for all media storage service exceptions
 * @author Luciano Mammino
 */
class MediaStorageServiceException extends \Exception
{
    protected $id;
    protected $name;
    protected $type;
    protected $size;
    
    public function __construct($message = "", $id = null, $name = null, $type = null, $size = null)
    {
        parent::__construct($message);
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->size = $size;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }
    
    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;
    }
    
}