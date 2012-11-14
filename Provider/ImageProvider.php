<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

use Symfony\Component\HttpFoundation\File\File;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Context\Context,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface,
    Oryzone\Bundle\MediaStorageBundle\Cdn\CdnInterface,
    Oryzone\Bundle\MediaStorageBundle\Exception\ProviderProcessException,
    Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException;

class ImageProvider extends Provider
{

    /**
     * @var string $tempDir
     */
    protected $tempDir;

    /**
     * @var \Imagine\Image\ImagineInterface $imagine
     */
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

    /**
     * Constructor
     *
     * @param string $tempDir
     * @param \Imagine\Image\ImagineInterface $imagine
     */
    public function __construct($tempDir, \Imagine\Image\ImagineInterface $imagine = NULL)
    {
        $this->checkTempDir($tempDir);
        $this->tempDir = $tempDir;
        $this->imagine = $imagine;
    }

    /**
     * Verifies if the temp directory exists and it tries to generate it otherwise
     *
     * @param string $tempDir
     * @throws \Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException
     */
    protected function checkTempDir($tempDir)
    {
        if(!is_dir($tempDir))
        {
            if(file_exists($tempDir))
                throw new InvalidArgumentException(
                    sprintf('Cannot generate temp folder "%s" for the ImageProvider. A file with the same path already exists', $tempDir));

            $filesystem = new \Symfony\Component\Filesystem\Filesystem();
            try
            {
                $filesystem->mkdir($tempDir);
            }
            catch(\Symfony\Component\Filesystem\Exception\IOException $e)
            {
                throw new InvalidArgumentException(
                    sprintf('Unable to create temp folder "%s" for the ImageProvider', $tempDir));
            }
        }
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
                throw new ProviderProcessException(sprintf('Cannot process image "%s": Imagine Bundle (avalanche123/imagine-bundle) not installed or misconfigured', $media), $media, $variant);

            $destFile = $this->tempDir . 'temp-' . $source->getFilename();

            /**
             * @var \Imagine\Image\ImageInterface $image
             */
            $image = $this->imagine->open( $source );
            $box = new \Imagine\Image\Box(200, 200);
            $image->resize($box);
            $image->save($destFile, array('quality' => 50));

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
