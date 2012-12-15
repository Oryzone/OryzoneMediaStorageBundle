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
     * @var string $namingStrategyName
     */
    protected $namingStrategyName;

    /**
     * @var string $defaultVariant
     */
    protected $defaultVariant;

    /**
     * @var array $variants
     */
    protected $variants;

    /**
     * Used to cache the latest generated tree
     *
     * @var VariantTree $lastTree
     */
    private $lastTree;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $providerName
     * @param string $filesystemName
     * @param string $cdnName
     * @param string $namingStrategyName
     * @param array  $variants
     * @param string $defaultVariant
     */
    public function __construct($name, $providerName, $filesystemName, $cdnName, $namingStrategyName, $variants = array(), $defaultVariant = 'default')
    {
        $this->cdnName = $cdnName;
        $this->filesystemName = $filesystemName;
        $this->name = $name;
        $this->providerName = $providerName;
        $this->namingStrategyName = $namingStrategyName;
        $this->variants = $variants;
        $this->defaultVariant = $defaultVariant;
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
    public function getNamingStrategyName()
    {
        return $this->namingStrategyName;
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultVariant()
    {
        return $this->defaultVariant;
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
    public function hasVariant($variantName)
    {
        return array_key_exists($variantName, $this->variants);
    }

    /**
     * {@inheritDoc}
     */
    public function buildVariantTree()
    {
        if($this->lastTree)

            return $this->lastTree;

        $tree = new VariantTree();
        foreach ($this->variants as $name => $v) {
            $variant = new Variant();
            $variant->setName($name);
            $variant->setMode($v['mode']);
            $variant->setOptions($v['process']);
            $tree->addNode($variant, $v['parent']);
        }

        $this->lastTree = $tree;

        return $tree;
    }
}
