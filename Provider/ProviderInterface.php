<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Cdn\CdnInterface,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface;

interface ProviderInterface
{
    /**
     * Gets the name of the provider
     *
     * @return string
     */
    public function getName();

    /**
     * Get an array representing the provider available options
     *
     * @return array
     */
    public function getRenderAvailableOptions();

    /**
     * Checks if the current provider supports a given Media
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media $media
     * @return boolean
     */
    public function supports(Media $media);

    /**
     * Executed each time a media is about to be saved, before the process method
     * Generally used to set metadata
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media $media
     * @return mixed
     */
    public function prepare(Media $media);

    /**
     * Process the media to create a variant
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media $media
     * @param \Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface $variant
     * @return mixed
     *
     * @throws \Oryzone\Bundle\MediaStorageBundle\Exception\ProviderProcessException if some errors occurs while processing
     */
    public function process(Media $media, VariantInterface $variant);

    /**
     * Renders a variant to HTML code. Useful for twig (or other template engines) integrations
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media $media
     * @param string $variantName
     * @param array $options
     * @param \Oryzone\Bundle\MediaStorageBundle\Cdn\CdnInterface $cdn
     * @return mixed
     */
    public function render(Media $media, $variantName, $options = array(), CdnInterface $cdn = NULL);

}
