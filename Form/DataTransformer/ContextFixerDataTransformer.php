<?php

namespace Oryzone\Bundle\MediaStorageBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

use Oryzone\MediaStorage\Model\MediaInterface;

class ContextFixerDataTransformer implements DataTransformerInterface
{
    /**
     * @var string $contextName
     */
    protected $contextName;

    /**
     * Constructor
     *
     * @param string $contextName
     */
    public function __construct($contextName)
    {
        $this->contextName = $contextName;
    }

    /**
     * {@inheritDoc}
     */
    public function transform($value)
    {
        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform($media)
    {
        if (!$media instanceof MediaInterface) {
            return $media;
        }

        if (!$media->getContext())
            $media->setContext($this->contextName);

        return $media;
    }
}