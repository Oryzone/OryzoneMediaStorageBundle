<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

use Symfony\Component\HttpFoundation\File\File;

use Oryzone\Bundle\MediaStorageBundle\Provider\Provider,
    Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Context\Context,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface;

class YoutubeProvider extends Provider
{

    /**
     * Regex to validate youtube video urls
     * @const string VALIDATION_REGEX_URL
     */
    const VALIDATION_REGEX_URL = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';

    /**
     * Regex to validate youtube ids
     * @const string VALIDATION_REGEX_ID
     */
    const VALIDATION_REGEX_ID = '%^[^"&?/ ]{11}$%i';

    protected $tempDir;

    protected $imagine;

    protected $buzz;

    /**
     * {@inheritDoc}
     */
    public function validateContent($content)
    {
        return preg_match(self::VALIDATION_REGEX_URL, $content) ||
                preg_match(self::VALIDATION_REGEX_ID, $content);
    }

    /**
     * {@inheritDoc}
     */
    public function prepare(Media $media, Context $context)
    {
        $id = NULL;
        if( preg_match(self::VALIDATION_REGEX_URL, $media->getContent(), $matches) )
            $id = $matches[1];
        else if( preg_match(self::VALIDATION_REGEX_ID, $media->getContent(), $matches) )
            $id = $matches[0];

        if($id !== NULL)
        {
            // TODO make request to youtube to get metadata

            // Store id into the metadata

            // download the preview and set it as content
        }
    }

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
    public function process(Media $media, VariantInterface $variant, File $source = NULL)
    {
        // TODO: Implement process() method.
    }

    /**
     * Renders a variant to HTML code. Useful for twig (or other template engines) integrations
     *
     * @param \Oryzone\Bundle\MediaStorageBundle\Model\Media              $media
     * @param \Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface $variant
     * @param string|null                                                 $url
     * @param array                                                       $options
     *
     * @return string
     */
    public function render(Media $media, VariantInterface $variant, $url = NULL, $options = array())
    {
        // TODO: Implement render() method.
    }
}
