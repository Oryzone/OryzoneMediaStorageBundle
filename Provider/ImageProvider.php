<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

use Symfony\Component\HttpFoundation\File\File;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Context\Context,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface,
    Oryzone\Bundle\MediaStorageBundle\Cdn\CdnInterface,
    Oryzone\Bundle\MediaStorageBundle\Exception\VariantProcessingException;

class ImageProvider extends Provider
{

    protected $tempDir;

    protected $imagine;

    /**
     * @var array
     */
    protected static $SUPPORTED_TYPES = array(
        'image/bmp',
        'image/gif',
        'image/jpeg',
        'image/png'
    );

    public function __construct($tempDir, Imagine\Image\ImagineInterface $imagine = NULL)
    {
        $this->tempDir = $tempDir;
        $this->imagine = $imagine;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsFile(File $file)
    {
        return in_array($file->getMimeType(), self::$SUPPORTED_TYPES);
    }

    /**
     * {@inheritDoc}
     */
    public function prepare(Media $media, Context $context)
    {
        // TODO: Implement prepare() method.
    }

    /**
     * {@inheritDoc}
     */
    public function process(Media $media, VariantInterface $variant, File $source = NULL)
    {
        $options = $variant->getOptions();
        if (is_array($options) && !empty($options)) {
            if($this->imagine == NULL)
                throw new VariantProcessingException(sprintf('Cannot process image "%s": Imagine Bundle (avalanche123/imagine-bundle) not installed or misconfigured', $media), $media, $variant);

            $destFile = $this->tempDir . 'temp-' . $source->getFilename();

            /**
             * @var \Imagine\Image\ImageInterface $image
             */
            $image = $this->imagine->open( $destFile );


            return new File($destFile);
        }

        return $source;
    }

    /**
     * {@inheritDoc}
     */
    public function render(Media $media, VariantInterface $variant, CdnInterface $cdn = NULL, $options = array())
    {
        // TODO: Implement render() method.
    }
}
