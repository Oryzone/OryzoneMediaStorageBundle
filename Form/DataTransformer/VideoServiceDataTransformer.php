<?php

namespace Oryzone\Bundle\MediaStorageBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

use Oryzone\MediaStorage\Model\MediaInterface;

class VideoServiceDataTransformer implements DataTransformerInterface
{

    /**
     * @var array $options
     */
    protected $urlSchema;

    /**
     * Constructor
     *
     * @param string $urlSchema
     */
    public function __construct($urlSchema)
    {
        $this->urlSchema = $urlSchema;
    }

    /**
     * {@inheritDoc}
     */
    public function transform($media)
    {
        if($media instanceof MediaInterface && $media->getContent() == NULL && $media->getMetaValue('id'))
            $media->setContent(sprintf($this->urlSchema, $media->getMetaValue('id')));

        return $media;
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($media)
    {
        // don't change
        return $media;
    }
}
