<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Cdn\CdnInterface,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface;

use Gaufrette\File;

interface ProviderInterface
{
    /**
     * Content type for file based providers
     */
    const CONTENT_TYPE_FILE = 0;

    /**
     * Content type for providers who use integer ids (numerical ids, like vimeo)
     */
    const CONTENT_TYPE_INT = 1;

    /**
     * Content type for providers who use string ids (like youtube)
     */
    const CONTENT_TYPE_STRING = 2;

    /**
     * Gets the name of the provider
     *
     * @return string
     */
    public function getName();

    /**
     * Get the content type of the current provider
     *
     * @return int
     */
    public function getContentType();

    /**
     * Checks if the current provider supports a given Media
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media $media
     * @param \Gaufrette\File|null                           $file
     *
     * @return boolean
     */
    public function supports(Media $media, File $file = NULL);

    /**
     * Executed each time a media is about to be saved, before the process method
     * Generally used to set metadata
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media $media
     * @param \Gaufrette\File|null                           $file
     *
     * @return mixed
     */
    public function prepare(Media $media, File $file = NULL);

    /**
     * Process the media to create a variant
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media              $media
     * @param \Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface $variant
     * @param \Gaufrette\File|null                                        $origin
     * @param \Gaufrette\File|null                                        $destination
     *
     * @return mixed
     */
    public function process(Media $media, VariantInterface $variant, File $origin = NULL, File $destination = NULL);

    /**
     * Renders a variant to HTML code. Useful for twig (or other template engines) integrations
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media              $media
     * @param \Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface $variant
     * @param \Oryzone\Bundle\MediaStorageBundle\Cdn\CdnInterface         $cdn
     * @param array                                                       $options
     *
     * @return string
     */
    public function render(Media $media, VariantInterface $variant, CdnInterface $cdn = NULL, $options = array());

}
