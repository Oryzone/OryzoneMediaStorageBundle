<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

abstract class Provider implements ProviderInterface
{

    /**
     * @var string $name
     */
    protected $name;

    /**
     * Get the provider name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}