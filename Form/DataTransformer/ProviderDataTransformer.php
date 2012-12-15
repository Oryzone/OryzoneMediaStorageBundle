<?php

namespace Oryzone\Bundle\MediaStorageBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

use Oryzone\Bundle\MediaStorageBundle\Provider\ProviderInterface,
    Oryzone\Bundle\MediaStorageBundle\Model\Media;

class ProviderDataTransformer implements DataTransformerInterface
{

    /**
     * @var \Oryzone\Bundle\MediaStorageBundle\Provider\ProviderInterface $provider
     */
    protected $provider;

    /**
     * @var array $options
     */
    protected $options;

    /**
     * Constructor
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Provider\ProviderInterface $provider
     * @param array $options
     */
    public function __construct(ProviderInterface $provider, array $options = array())
    {
        $this->provider = $provider;
        $this->options = $options;
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
        if (!$media instanceof Media) {
            return $media;
        }

        if (!$media->getContext() && isset($this->options['context'])) {
            $media->setContext($this->options['context']);
        }

        $this->provider->transform($media);

        return $media;
    }
}