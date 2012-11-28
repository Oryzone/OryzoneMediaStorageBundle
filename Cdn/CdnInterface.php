<?php

namespace Oryzone\Bundle\MediaStorageBundle\Cdn;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface;

interface CdnInterface
{
    /**
     * Sets an array of options
     *
     * @param  array                                                                 $configuration
     * @return mixed
     * @throws \Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException if the options array is not valid
     */
    public function setConfiguration($configuration);

    /**
     * Retrieves the public url of the media on the current CDN
     *
     * @param  \Oryzone\Bundle\MediaStorageBundle\Model\Media              $media
     * @param  \Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface $variant
     * @param   array                                                      $options
     * @return string
     */
    public function getUrl(Media $media, VariantInterface $variant, $options = array());
}
