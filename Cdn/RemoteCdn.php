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
    public function setOptions($options)
    {
        if(!isset($options['base_url']))
            throw new InvalidArgumentException('Missing mandatory "base_url" option');

        $this->baseUrl = $options['base_url'];
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl(Media $media, VariantInterface $variant)
    {
        return $this->baseUrl . $variant->getFilename();
    }
}
