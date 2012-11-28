<?php

namespace Oryzone\Bundle\MediaStorageBundle\Cdn;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface,
    Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException;

class RemoteCdn implements CdnInterface
{
    /**
     * @var string $baseUrl
     */
    protected $baseUrl;

    /**
     * {@inheritDoc}
     */
    public function setConfiguration($configuration)
    {
        if(!isset($configuration['base_url']))
            throw new InvalidArgumentException('Missing mandatory "base_url" option');

        $this->baseUrl = $configuration['base_url'];
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl(Media $media, VariantInterface $variant, $options = array())
    {
        return $this->baseUrl . $variant->getFilename();
    }
}
