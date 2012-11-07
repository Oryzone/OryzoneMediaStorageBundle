<?php

namespace Oryzone\Bundle\MediaStorageBundle\NamingStrategy;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface,
    Gaufrette\Filesystem;

interface NamingStrategyInterface
{

    /**
     * Generates a name for a file to be stored.
     * Note: should not add file extension
     *
     * @param  \Oryzone\Bundle\MediaStorageBundle\Model\Media              $media
     * @param  \Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface $variant
     * @param  \Gaufrette\Filesystem                                       $filesystem
     * @return string
     */
    public function generateName(Media $media, VariantInterface $variant, Filesystem $filesystem);
}
