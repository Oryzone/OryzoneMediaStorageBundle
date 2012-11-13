<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface,
    Oryzone\Bundle\MediaStorageBundle\Cdn\CdnInterface,
    Oryzone\Bundle\MediaStorageBundle\Context\Context;

use Symfony\Component\HttpFoundation\File\File;

class FileProvider extends Provider
{

    protected $name = 'file';

    /**
     * {@inheritDoc}
     */
    public function supports(Media $media, File $file = NULL)
    {
        return ($file != NULL && $file->exists());
    }

    /**
     * {@inheritDoc}
     */
    public function prepare(Media $media, Context $context)
    {
        // nothing to do
    }

    /**
     * {@inheritDoc}
     */
    public function process(Media $media, VariantInterface $variant, File $source = NULL)
    {
        $media->setMetadataValue($variant->getName().'.size', $source->getSize());

        return $source;
    }

    /**
     * {@inheritDoc}
     */
    public function render(Media $media, VariantInterface $variant, CdnInterface $cdn = NULL, $options = array())
    {
        $url = $cdn->getUrl($media, $variant);
        $sizeKey = $variant->getName() . '.size';

        $attributes = array(
            'title' => $media->getName(). ' ('. $media->getMetadataValue($sizeKey) . ')'
        );
        if(isset($options['attributes']))
            $attributes = array_merge($attributes, $options['attributes']);

        $htmlAttributes = '';
        if(isset($options['attributes']))
            foreach($attributes as $key => $value)
                $htmlAttributes .= $key . '="' . $value . '" ';

        return sprintf('<a href="%s" %s>%s</a>',
                            $url, $htmlAttributes, $media->getName());
    }
}
