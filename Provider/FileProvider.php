<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface,
    Oryzone\Bundle\MediaStorageBundle\Cdn\CdnInterface;

use Gaufrette\File;

class FileProvider extends Provider
{

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
    public function prepare(Media $media, File $file = NULL)
    {
        // nothing to do
    }

    /**
     * {@inheritDoc}
     */
    public function process(Media $media, VariantInterface $variant, File $origin = NULL, File $destination = NULL)
    {
        //TODO find a reliable way to read mime type from a gaufrette file
        //$media->setMetadata($variant->getName().'.contentType', mime_content_type($origin));
        $media->setMetadata($variant->getName().'.size', $origin->getSize());
        $destination->setContent($origin->getContent());
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
