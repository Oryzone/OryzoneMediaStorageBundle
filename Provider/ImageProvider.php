<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

use Symfony\Component\HttpFoundation\File\File;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Context\Context,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface,
    Oryzone\Bundle\MediaStorageBundle\Cdn\CdnInterface,
    Oryzone\Bundle\MediaStorageBundle\Context\ContextInterface,
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
     * @var array
     */
    protected static $DEFAULT_OPTIONS = array(
        'width'         => NULL,
        'height'        => NULL,
        'resize'        => 'stretch',
        'format'        => 'jpg',
        'quality'       => 100,
        'enlarge'       => TRUE
    );

    protected static $ALLOWED_RESIZE_MODES = array(
        'stretch', 'proportional', 'crop'
    );

    protected static $ALLOWED_FORMATS = array(
        'bmp', 'gif', 'jpg', 'jpeg', 'png'
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
     * Process options array by validating it and merging with default values
     *
     * @param array $options
     * @param string $variantName
     * @param string $contextName
     * @throws \Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException
     *
     * @return array
     */
    protected function processOptions($options, $variantName, $contextName)
    {
        // validates options for unsupported keys
        $allowedKeys = array_keys(self::$DEFAULT_OPTIONS);
        foreach($options as $key => $value)
        {
            if(!in_array($key, $allowedKeys))
                throw new InvalidArgumentException(
                    sprintf('Unsupported option "%s" for variant "%s" in context "%s". Allowed values are: %s',
                        $key, $variantName, $contextName, json_encode($allowedKeys)));
        }

        $options = array_merge(self::$DEFAULT_OPTIONS, $options);
        if(!in_array($options['resize'], self::$ALLOWED_RESIZE_MODES))
            throw new InvalidArgumentException(
                sprintf('Unsupported value "%s" for key "resize" for variant "%s" in context "%s". Allowed values are: %s',
                    $options['resize'], $variantName, $contextName, json_encode(self::$ALLOWED_RESIZE_MODES)));

        if(!in_array($options['format'], self::$ALLOWED_FORMATS))
            throw new InvalidArgumentException(
                sprintf('Unsupported value "%s" for key "format" for variant "%s" in context "%s". Allowed values are: %s',
                    $options['format'], $variantName, $contextName, json_encode(self::$ALLOWED_FORMATS)));

        if(!is_int($options['quality']) || $options['quality'] < 1 || $options['quality'] > 100)
            throw new InvalidArgumentException(
                sprintf('Unsupported value "%s" for key "quality" for variant "%s" in context "%s". Allowed values are integer values between 1 and 100',
                    $options['quality'], $variantName, $contextName));

        return $options;
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

            $options = $this->processOptions($options, $variant->getName(), $media->getContext());

            $destFile = sprintf('%s%s-temp-%s.%s',
                $this->tempDir, date('Y-m-d-h-i-s'), $source->getBasename('.'.$source->getExtension()), $options['format']);

            /**
             * @var \Imagine\Image\ImageInterface $image
             */
            $image = $this->imagine->open( $source );

            list($originalWidth, $originalHeight) = getimagesize($source->getPathName());
            $width = $options['width'];
            $height = $options['height'];

            if(
                $options['enlarge'] === TRUE ||
                ($originalWidth >= $width && $originalHeight >= $height)
            )
            {
                if( $options['resize'] == 'proportional' )
                {
                    //calculate missing dimension
                    if($width === NULL)
                        $width = round( $originalWidth * $height / $originalHeight );
                    elseif($height === NULL)
                        $height = round( $width * $originalHeight / $originalWidth );
                }

                $box = new \Imagine\Image\Box($width, $height);

                if($options['resize'] == 'proportional' || $options['resize'] == 'stretch')
                    $image->resize($box);
                elseif( $options['resize'] == 'crop' )
                    $image = $image->thumbnail($box, \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
            }

            $image->save($destFile, array('quality' => $options['quality']));

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
