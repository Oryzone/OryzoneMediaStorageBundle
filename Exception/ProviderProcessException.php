<?php

namespace Oryzone\Bundle\MediaStorageBundle\Exception;

use Oryzone\Bundle\MediaStorageBundle\Provider\ProviderInterface,
    Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface;

class ProviderProcessException extends MediaStorageException
{
    /**
     * @var \Oryzone\Bundle\MediaStorageBundle\Provider\ProviderInterface $provider
     */
    protected $provider;

    /**
     * @var \Oryzone\Bundle\MediaStorageBundle\Model\Media $media
     */
    protected $media;

    /**
     * @var \Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface
     */
    protected $variant;

    /**
     * Constructor
     *
     * @param string $message
     * @param \Oryzone\Bundle\MediaStorageBundle\Provider\ProviderInterface $provider
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media $media
     * @param \Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface $variant
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($message = "", ProviderInterface $provider = NULL, Media $media = NULL, VariantInterface $variant = NULL, $code = 0, \Exception $previous = null)
    {
        $this->provider = $provider;
        $this->media = $media;
        $this->variant = $variant;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Get the media
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\Model\Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Get the provider
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\Provider\ProviderInterface
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * Get the variant
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface
     */
    public function getVariant()
    {
        return $this->variant;
    }

}
