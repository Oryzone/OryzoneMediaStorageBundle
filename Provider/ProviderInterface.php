<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Cdn\CdnInterface,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface,
    Oryzone\Bundle\MediaStorageBundle\Context\Context;

use Symfony\Component\HttpFoundation\File\File;

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
     * @param \Symfony\Component\HttpFoundation\File\File $file
     *
     * @return boolean
     */
    public function supportsFile(File $file);

    /**
     * Executed each time a media is about to be saved, before the process method
     * Generally used to set metadata
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media     $media
     * @param \Oryzone\Bundle\MediaStorageBundle\Context\Context $context
     *
     * @return mixed
     */
    public function prepare(Media $media, Context $context);

    /**
     * Process the media to create a variant. Should return a <code>File</code> instance referring
     * the resulting file
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media              $media
     * @param \Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface $variant
     * @param \Symfony\Component\HttpFoundation\File\File                 $source
     *
     * @return File|null
     */
    public function process(Media $media, VariantInterface $variant, File $source = NULL);

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

    /**
     * Removes any temp file stored by the current provider instance
     */
    public function removeTempFiles();

}
