<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface,
    Oryzone\Bundle\MediaStorageBundle\Context\Context;

use Symfony\Component\HttpFoundation\File\File;

class FileProvider extends Provider
{

    protected $name = 'file';

    /**
     * {@inheritDoc}
     */
    public function validateContent($content)
    {
        if(is_string($content))
            $content = new File($content);
        
        return ($content instanceof File && $content->isFile());
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
        $variant->setMetaValue('size', $source->getSize());
        return $source;
    }

    /**
     * {@inheritDoc}
     */
    public function render(Media $media, VariantInterface $variant, $url = NULL, $options = array())
    {
        $attributes = array(
            'title' => $media->getName(). ' ('. $variant->getMetaValue('size') . ')'
        );
        if(isset($options['attributes']))
            $attributes = array_merge($attributes, $options['attributes']);

        $htmlAttributes = '';
        if(isset($options['attributes']))
            foreach($attributes as $key => $value)
                if($value !== NULL)
                    $htmlAttributes .= $key . '="' . $value . '" ';

        return sprintf('<a href="%s" %s>%s</a>',
                            $url, $htmlAttributes, $media->getName());
    }
}
