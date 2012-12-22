<?php

namespace Oryzone\Bundle\MediaStorageBundle\Provider;

use Oryzone\Bundle\MediaStorageBundle\Model\Media,
    Oryzone\Bundle\MediaStorageBundle\Context\Context,
    Oryzone\Bundle\MediaStorageBundle\Exception\ProviderProcessException,
    Oryzone\Bundle\MediaStorageBundle\Variant\VariantInterface,
    Oryzone\Bundle\MediaStorageBundle\Exception\InvalidArgumentException;

class VimeoProvider extends VideoServiceProvider
{
    protected $name = 'vimeo';

    /**
     * Regex for validating vimeo urls
     * @const string VALIDATION_REGEX_URL
     */
    const VALIDATION_REGEX_URL = '%^https?://(?:www\.)?vimeo\.com/(?:m/)?(\d+)(?:.*)?$%i';

    /**
     * Regex for validating vimeo ids
     * @const string VALIDATION_REGEX_ID
     */
    const VALIDATION_REGEX_ID = '%^\d+$%';

    /**
     * Api url schema
     * @const string API_URL
     */
    const API_URL = 'http://vimeo.com/api/v2/video/%s.xml';

    /**
     * Canonical url schema
     * @const string CANONICAL_URL
     */
    const CANONICAL_URL = 'http://vimeo.com/%s';

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

            /**
             * @var \Buzz\Message\Response $response
             */
            $response = $this->buzz->get($apiUrl);

            if($response->isClientError() || $response->isServerError())
                throw new ProviderProcessException(sprintf('Cannot find Vimeo video "%s"', $videoUrl), $this, $media);

            //parse vimeo metadata
            $title = NULL;
            $description = NULL;
            $tags = NULL;

            $doc = new \DOMDocument();
            $doc->loadXML($response->getContent());

            $xpath = new \DOMXpath($doc);

            //preview image
            $elements = $xpath->query('/videos/video/thumbnail_large');
            if(is_null($elements) || $elements->length == 0)
                throw new ProviderProcessException(sprintf('Cannot find preview image for Vimeo video "%s"', $videoUrl), $this, $media);

            $previewImageUrl = $elements->item(0)->nodeValue;
            $previewImageFile = sprintf('%svimeo_preview_%s.jpg', $this->tempDir, $id);
            $this->addTempFile($previewImageFile);
            if(!file_exists($previewImageFile))
                $this->downloadFile($previewImageUrl, $previewImageFile, $media);
            $media->setContent($previewImageFile);

            $title = NULL;
            $description = NULL;
            $tags = NULL;

            //title
            $elements = $xpath->query('/videos/video/title');
            if (!is_null($elements) && $elements->length > 0)
                $title = $elements->item(0)->nodeValue;

            //description
            $elements = $xpath->query('/videos/video/description');
            if (!is_null($elements) && $elements->length > 0)
                $description = strip_tags($elements->item(0)->nodeValue);

            //tags
            $elements = $xpath->query('/videos/video/tags');
            if(!is_null($elements) && $elements->length > 0)
                $tags = explode(", ", $elements->item(0)->nodeValue);

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
                        'allowfullscreen' => '',
                        'webkitAllowFullScreen' => '',
                        'mozallowfullscreen' => ''
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
            $code = sprintf('<iframe src="http://player.vimeo.com/video/%s" %s></iframe>', $media->getMetaValue('id'), $htmlAttributes);
        else
            $code = sprintf('<img src="%s" %s/>', $url, $htmlAttributes);

        return $code;
    }
}
