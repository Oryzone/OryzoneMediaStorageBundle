<?php

namespace Oryzone\Bundle\MediaStorageBundle\Model;

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
     * The name of the provider
     *
     * @var string $provider
     */
    protected $provider;

    /**
     * Structured array of available variants
     *
     * @var array $variants
     */
    protected $variants;

    /**
     * Structured array of metadata
     *
     * @var array $metadata
     */
    protected $metadata;

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
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    /**
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param array $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;
    }

    /**
     * @return array
     */
    public function getMetadata()
    {
        return $this->metadata;
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
    public function getMetadataValue($key, $default = NULL)
    {
        if(is_array($this->metadata) && isset($this->metadata[$key]))

            return $this->metadata[$key];

        return $default;
    }

    /**
     * Sets a metadata value
     *
     * @param string $key
     * @param mixed  $value
     */
    public function setMetadataValue($key, $value)
    {
        if(!is_array($this->metadata))
            $this->metadata = array();

        $this->metadata[$key] = $value;
    }

    /**
     * @param \DateTime $modifiedAt
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->modifiedAt = $modifiedAt;
    }

    /**
     * @return \DateTime
     */
    public function getModifiedAt()
    {
        return $this->modifiedAt;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return string
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param array $variants
     */
    public function setVariants($variants)
    {
        $this->variants = $variants;
    }

    /**
     * @return array
     */
    public function getVariants()
    {
        return $this->variants;
    }

    /**
     * Checks if the media has a given variant
     *
     * @param  string $variantName
     * @return bool
     */
    public function hasVariant($variantName)
    {
        return array_key_exists($variantName, $this->variants);
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
}
