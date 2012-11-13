<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

use Symfony\Component\HttpFoundation\File\File;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Context\Context,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface,
    Oryzone\Bundle\MediaStorageBundle\Cdn\CdnInterface;

class ImageProvider extends Provider
{

    /**
     * @var array
     */
    protected static $SUPPORTED_TYPES = array(
        'image/bmp',
        'image/gif',
        'image/jpeg',
        'image/png'
    );

    /**
     * {@inheritDoc}
     */
    public function supportsFile(File $file)
    {
        return in_array($file->getMimeType(), self::$SUPPORTED_TYPES);
    }

    /**
     * {@inheritDoc}
     */
    public function prepare(Media $media, Context $context)
    {
        // TODO: Implement prepare() method.
    }

    /**
     * {@inheritDoc}
     */
    public function process(Media $media, VariantInterface $variant, File $source = NULL)
    {
        // TODO: Implement process() method.
    }

    /**
     * {@inheritDoc}
     */
    public function render(Media $media, VariantInterface $variant, CdnInterface $cdn = NULL, $options = array())
    {
        // TODO: Implement render() method.
    }
}
