<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

use Symfony\Component\Form\FormBuilderInterface;

use Buzz\Browser;

use Imagine\Image\ImagineInterface;

use Oryzone\Bundle\MediaStorageBundle\Exception\ProviderPrepareException,
    Oryzone\Bundle\MediaStorageBundle\Model\Media;

abstract class VideoServiceProvider extends ImageProvider
{

    /**
     * @var \Buzz\Client\AbstractClient $buzz
     */
    protected $buzz;

    /**
     * Constructor
     *
     * @param $tempDir
     * @param \Imagine\Image\ImagineInterface $imagine
     * @param \Buzz\Browser $buzz
     */
    public function __construct($tempDir, ImagineInterface $imagine, Browser $buzz)
    {
        parent::__construct($tempDir, $imagine);
        $this->buzz = $buzz;
    }

    /**
     * Downloads a file from an url to a given destination
     * (Usually used to download preview images)
     *
     * @param $url
     * @param $destination
     * @param Media $media
     * @throws \Oryzone\Bundle\MediaStorageBundle\Exception\ProviderPrepareException
     * @return void
     */
    protected function downloadFile($url, $destination, Media $media = NULL)
    {
        try
        {
            $fp = fopen($destination, 'w');
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);
        }
        catch(\Exception $e)
        {
            throw new ProviderPrepareException(sprintf('Cannot downoad file "%s": $s', $url, $e->getMessage()), $this, $media);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function buildMediaType(FormBuilderInterface $formBuilder, array $options = array())
    {
        $fieldOptions = array();
        if(isset($options['edit']) && $options['edit'] == TRUE)
            $fieldOptions = array('required' => FALSE);

        $formBuilder->add('content', 'text', $fieldOptions);
    }
}
