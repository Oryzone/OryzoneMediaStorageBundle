<?php

namespace Oryzone\Bundle\MediaStorageBundle\Exception;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface;

class VariantProcessingException extends MediaStorageException
{

    /**
     * @var \Oryzone\Bundle\MediaStorageBundle\Model\Media $media
     */
    protected $media;

    /**
     * @var \Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface $variant
     */
    protected $variant;

    /**
     * Constructor
     *
     * @param string $message
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media $media
     * @param \Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface $variant
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct($message = "", Media $media, VariantInterface $variant, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->media = $media;
        $this->variant = $variant;
    }

    /**
     * Get media
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\Model\Media
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Get variant
     *
     * @return \Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface
     */
    public function getVariant()
    {
        return $this->variant;
    }

}
