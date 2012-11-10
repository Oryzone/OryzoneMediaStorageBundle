<?php

namespace Oryzone\Bundle\MediaStorageBundle\Context;

use Oryzone\Bundle\MediaStorageBundle\Variant\VariantTree,
    Oryzone\Bundle\MediaStorageBundle\Variant\Variant;

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
     * @param array  $variants
     */
    public function __construct($name, $providerName, $filesystemName, $cdnName, $variants = array())
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

    /**
     * {@inheritDoc}
     */
    public function buildVariantTree()
    {
        $tree = new VariantTree();
        foreach($this->variants as $name => $v)
        {
            $variant = new Variant();
            $variant->setName($name);
            $variant->setMode($v['mode']);
            $variant->setOptions($v['process']);
            $tree->addNode($variant, $v['parent']);
        }

        return $tree;
    }
}
