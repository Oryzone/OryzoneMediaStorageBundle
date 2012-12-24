<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

use Symfony\Component\Form\FormBuilderInterface;
use Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException;

use Oryzone\Bundle\MediaStorageBundle\Provider\Provider,
    Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Context\Context,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface,
    Oryzone\Bundle\MediaStorageBundle\Exception\ProviderProcessException;

class YoutubeProvider extends VideoServiceProvider
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
     * Tries to extract the video id from a string (generally the media content)
     *
     * @param $content
     * @return string|NULL
     */
    protected function getIdFromContent($content)
    {
        $id = NULL;
        if( preg_match(self::VALIDATION_REGEX_URL, $content, $matches) )
            $id = $matches[1];
        else if( preg_match(self::VALIDATION_REGEX_ID, $content, $matches) )
            $id = $matches[0];

        return $id;
    }

    /**
     * {@inheritDoc}
     */
    public function hasChangedContent(Media $media)
    {
        return ($media->getContent() != NULL && $this->getIdFromContent($media) !== $media->getMetaValue('id'));
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
        $id = $this->getIdFromContent($media->getContent());

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
                throw new ProviderProcessException(sprintf('Cannot find Youtube video "%s"', $videoUrl), $this, $media);

            $previewImageFile = sprintf('%syoutube_preview_%s.jpg', $this->tempDir, $id);
            $this->addTempFile($previewImageFile);
            if(!file_exists($previewImageFile))
                $this->downloadFile($previewImageUrl, $previewImageFile, $media);

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
        $defaultOptions = array(
            'mode' => 'video',
            'attributes' => array()
        );

        $options = array_merge($defaultOptions, $options);

        if($options['mode'] != 'video' && $options['mode'] != 'image')
            throw new InvalidArgumentException(sprintf('Invalid mode "%s" to render a Youtube Video. Allowed values: "image", "video"', $options['mode']) );

        switch($options['mode'])
        {
            case 'video':
                $options['attributes'] = array_merge(
                    array(
                        'width' => $variant->getMetaValue('width', 420),
                        'height'=> $variant->getMetaValue('height', 315),
                        'frameborder' => 0,
                        'allowfullscreen' => ''
                    ), $options['attributes']);
                break;

            case 'image':
                $options['attributes'] = array_merge(
                    array(
                        'title' => $media->getName(),
                        'width' => $variant->getMetaValue('width', 420),
                        'height'=> $variant->getMetaValue('height', 315),
                    ), $options['attributes']
                );
                break;
        }

        $htmlAttributes = '';
        if(isset($options['attributes']))
            foreach($options['attributes'] as $key => $value)
                if($value !== NULL)
                    $htmlAttributes .= $key . ($value !== '' ?('="' . $value. '"'):'') . ' ';

        if($options['mode'] == 'video')
            $code = sprintf('<iframe src="http://www.youtube.com/embed/%s" %s></iframe>', $media->getMetaValue('id'), $htmlAttributes);
        else
            $code = sprintf('<img src="%s" %s/>', $url, $htmlAttributes);

        return $code;
    }
}
