<?php

namespace Oryzone\Bundle\MediaStorageBundle\Cdn;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface;

interface CdnInterface
{
    /**
     * Sets an array of options
     *
     * @param  array                                                                 $options
     * @return mixed
     * @throws \Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException if the options array is not valid
     */
    public function setOptions($options);

    /**
     * Retrieves the public url of the media on the current CDN
     *
     * @param  \Oryzone\Bundle\MediaStorageBundle\Model\Media              $media
     * @param  \Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface $variant
     * @return string
     */
    public function getUrl(Media $media, VariantInterface $variant);
}
