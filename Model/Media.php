<?php

namespace Oryzone\Bundle\MediaStorageBundle\Model;

use Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface,
    Oryzone\Bundle\MediaStorageBundle\Variant\Variant,
    Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException;

abstract class Media
{
    /**
     * A descriptive name
     *
     * @var string $name
     */
    protected $name;

    /**
     * The content of the media (a reference to a file or binary data)
     *
     * @var mixed $content
     */
    protected $content;

    /**
     * The name of the context
     *
     * @var string $context
     */
    protected $context;

    /**
     * Structured array of available variants
     *
     * @var array $variants
     */
    protected $variants;

    /**
     * Structured array of metadata
     *
     * @var array $meta
     */
    protected $meta;

    /**
     * Media creation date
     *
     * @var \DateTime $createdAt
     */
    protected $createdAt;

    /**
     * Media last modification date
     *
     * @var \DateTime $modifiedAt
     */
    protected $modifiedAt;

    /**
     * Constructor
     */
    public function __construct($content = NULL, $contextName = NULL)
    {
        $this->content = $content;
        $this->context = $contextName;
        $this->createdAt = $this->modifiedAt = new \DateTime();
    }

    /**
     * Set content
     *
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
        $this->modifiedAt = new \DateTime();
    }

    /**
     * Get content
     *
     * @return mixed|null
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set context
     *
     * @param string $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * Get context
     *
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Set created at
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * get created at
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set metadata array
     *
     * @param array $meta
     */
    public function setMeta($meta)
    {
        $this->meta = $meta;
    }

    /**
     * Get metadata array
     *
     * @return array
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Get a metadata value for a given key
     *
     * @param string     $key
     * @param mixed|null $default will return this value if the given key does not exist
     * in the metadata array
     *
     * @return mixed|null
     */
    public function getMetaValue($key, $default = NULL)
    {
        if(is_array($this->meta) && isset($this->meta[$key]))

            return $this->meta[$key];

        return $default;
    }

    /**
     * Sets a metadata value
     *
     * @param string $key
     * @param mixed  $value
     */
    public function setMetaValue($key, $value)
    {
        if(!is_array($this->meta))
            $this->meta = array();

        $this->meta[$key] = $value;
    }

    /**
     * Set modified at
     *
     * @param \DateTime $modifiedAt
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;
    }

    /**
     * Get modified at
     *
     * @return \DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Checks if the current media has a variant with a given name
     *
     * @param  string $name
     * @return bool
     */
    public function hasVariant($name)
    {
        return array_key_exists($name, $this->variants);
    }

    /**
     * Set a variant with a given name
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface $variant
     */
    public function addVariant(VariantInterface $variant)
    {
        $this->variants[$variant->getName()] = $variant->toArray();
    }

    /**
     * Remove a variant with a given name
     *
     * @param  string $variantName
     * @return bool
     */
    public function removeVariant($variantName)
    {
        if (array_key_exists($variantName, $this->variants)) {
            unset($this->variants[$variantName]);

            return true;
        }

        return false;
    }

    /**
     * Set variants
     *
     * @param array $variants
     */
    public function setVariants($variants)
    {
        $this->variants = $variants;
    }

    /**
     * Get variants
     *
     * @return array
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * Creates a <code>Variant</code> instance for a given variant
     *
     * @param $variantName
     * @return \Oryzone\Bundle\MediaStorageBundle\Variant\Variant|\Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface
     * @throws \Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException
     */
    public function getVariantInstance($variantName)
    {
        if(!array_key_exists($variantName, $this->variants))
            throw new InvalidArgumentException(sprintf('media "%s" has no variant named "%s" ', $this, $variantName));

        return Variant::fromArray($this->variants[$variantName]);
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        return sprintf('Media (%s) - %s', get_class($this), $this->name);
    }

}
