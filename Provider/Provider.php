<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

abstract class Provider implements ProviderInterface
{

    /**
     * Default content type (file).
     * Can be redefined in subclasses without the need to redefine the getContentType method
     *
     * @var int
     */
    protected static $contentType = self::CONTENT_TYPE_FILE;

    /**
     * @var string $name
     */
    protected $name;

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getContentType()
    {
        return self::$contentType;
    }

}
