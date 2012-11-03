<?php

namespace Oryzone\Bundle\MediaStorageBundle\Context;

class Context implements ContextInterface
{

    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var string $providerName
     */
    protected $providerName;

    /**
     * @var string filesystemName
     */
    protected $filesystemName;

    /**
     * @var string $cdnName
     */
    protected $cdnName;

    /**
     * @var array $variants
     */
    protected $variants;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $providerName
     * @param string $filesystemName
     * @param string $cdnName
     * @param array $variants
     */
    function __construct($name, $providerName, $filesystemName, $cdnName, $variants = array())
    {
        $this->cdnName = $cdnName;
        $this->filesystemName = $filesystemName;
        $this->name = $name;
        $this->providerName = $providerName;
        $this->variants = $variants;
    }


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
    public function getProviderName()
    {
        return $this->providerName;
    }

    /**
     * {@inheritDoc}
     */
    public function getFilesystemName()
    {
        return $this->filesystemName;
    }

    /**
     * {@inheritDoc}
     */
    public function getCdnName()
    {
        return $this->cdnName;
    }

    /**
     * {@inheritDoc}
     */
    public function getVariants()
    {
        return $this->variants;
    }
}