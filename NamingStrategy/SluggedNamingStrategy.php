<?php

namespace Oryzone\Bundle\MediaStorageBundle\NamingStrategy;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface,
    Gaufrette\Filesystem,
    Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException;

class SluggedNamingStrategy extends NamingStrategy
{

    /**
     * {@inheritDoc}
     */
    public function generateName(Media $media, VariantInterface $variant, Filesystem $filesystem)
    {
        if( trim($media->getName()) == '' )
            throw new InvalidArgumentException('The given media has no name');

        $name = self::urlize($media->getName());
        $uid = uniqid('_'.$variant->getName());

        return $name.$uid;
    }
}
