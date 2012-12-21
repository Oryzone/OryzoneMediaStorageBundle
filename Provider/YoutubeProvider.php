<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

use Symfony\Component\Form\FormBuilderInterface;

use Buzz\Browser;

use Imagine\Image\ImagineInterface;

use Oryzone\Bundle\MediaStorageBundle\Provider\Provider,
    Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Context\Context,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface,
    Oryzone\Bundle\MediaStorageBundle\Exception\ProviderProcessException;

class YoutubeProvider extends ImageProvider
{
    protected $name = 'youtube';

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

    /**
     * Url scheme to retrieve video data
     * @const string API_URL
     */
    const API_URL = 'http://gdata.youtube.com/feeds/api/videos/%s';

    /**
     * Canonical url scheme to watch youtube video
     * @const string CANONICAL_URL
     */
    const CANONICAL_URL = 'http://www.youtube.com/?v=%s';

    /**
     * Url scheme for the video preview image
     * @const string PREVIEW_IMAGE_URL
     */
    const PREVIEW_IMAGE_URL = 'http://img.youtube.com/vi/%s/0.jpg';

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

    protected function downloadFile($url, $destination)
    {
        $fp = fopen($destination, 'w');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }

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
            $apiUrl = sprintf(self::API_URL, $id);
            $videoUrl = sprintf(self::CANONICAL_URL, $id);
            $previewImageUrl = sprintf(self::PREVIEW_IMAGE_URL, $id);

            /**
             * @var \Buzz\Message\Response $response
             */
            $response = $this->buzz->get($apiUrl);

            if($response->isClientError() || $response->isServerError())
                throw new ProviderProcessException(sprintf('Cannot Youtube find video "%s"', $videoUrl), $this, $media);

            $previewImageFile = sprintf('%syoutube_preview_%s.jpg', $this->tempDir, $id);
            $this->addTempFile($previewImageFile);
            if(!file_exists($previewImageFile))
                $this->downloadFile($previewImageUrl, $previewImageFile);

            $media->setContent($previewImageFile);

            //parse youtube metadata
            $title = NULL;
            $description = NULL;
            $tags = NULL;

            $doc = new \DOMDocument();
            $doc->loadXML($response->getContent());

            $xpath = new \DOMXpath($doc);
            $xpath->registerNamespace('a', 'http://www.w3.org/2005/Atom');

            // title
            $elements = $xpath->query('/a:entry/a:title');
            if (!is_null($elements) && $elements->length > 0)
                $title = $elements->item(0)->nodeValue;

            //description
            $elements = $xpath->query('/a:entry/a:content');
            if (!is_null($elements) && $elements->length > 0)
                $description = $elements->item(0)->nodeValue;

            //tags
            $elements = $xpath->query('(/a:entry/a:category/@term)[position()>1]');
            if(!is_null($elements) && $elements->length > 0)
            {
                $tags = array();
                foreach($elements as $element)
                    $tags[] = $element->nodeValue;
            }

            $media->setMetaValue('id', $id);
            if($title)
                $media->setMetaValue('title', $title);
            if($description)
                $media->setMetaValue('description', $description);
            if($tags)
                $media->setMetaValue('tags', $tags);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function render(Media $media, VariantInterface $variant, $url = NULL, $options = array())
    {
        // TODO: change this
        return parent::render($media, $variant, $url, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function buildMediaType(FormBuilderInterface $formBuilder, array $options = array())
    {
        $formBuilder->add('content', 'text');
    }
}
