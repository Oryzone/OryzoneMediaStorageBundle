<?php

namespace Oryzone\Bundle\MediaStorageBundle\Cdn;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface,
    Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException;

class LocalCdn implements CdnInterface
{
    /**
     * @var string $path
     */
    protected $path;

    /**
     * {@inheritDoc}
     */
    public function setOptions($options)
    {
        if(!isset($options['path']))
            throw new InvalidArgumentException('Missing mandatory "path" option');

        $this->path = $options['path'];
    }

    /**
     * {@inheritDoc}
     */
    public function getUrl(Media $media, VariantInterface $variant)
    {
        return $this->path . $variant->getFilename();
    }
}
