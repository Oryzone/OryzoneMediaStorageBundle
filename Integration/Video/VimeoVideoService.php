<?php

namespace Oryzone\Bundle\MediaStorageBundle\Integration\Video;

use Oryzone\Bundle\MediaStorageBundle\Exception\Integration\ResourceNotFoundException;

class VimeoVideoService extends VideoService
{
    /**
     * Url scheme for the API requests
     */
    const API_URL = 'http://vimeo.com/api/v2/video/%s.xml';

    /**
     * Scheme for xpath queries
     */
    const XPATH_SCHEME = '/videos/video/%s';

    /**
     * The available metadata array
     *
     * @var array $AVAILABLE_METADATA
     */
    protected static $AVAILABLE_METADATA = array(
        'id',
        'title',
        'description',
        'url',
        'upload_date',
        'mobile_url',
        'thumbnail_small',
        'thumbnail_medium',
        'thumbnail_large',
        'user_id',
        'user_name',
        'user_url',
        'user_portrait_small',
        'user_portrait_medium',
        'user_portrait_large',
        'user_portrait_huge',
        'stats_number_of_likes',
        'stats_number_of_plays',
        'stats_number_of_comments',
        'duration',
        'width',
        'height',
        'tags',
        'embed_privacy',
    );

    /**
     * Holds the DOMDocument used for XML parsing
     *
     * @var \DOMDocument $document
     */
    protected $document;

    /**
     * Used to extract data from the DOMDocument
     *
     * @var \DOMXpath $xpath
     */
    protected $xpath;

    /**
     * {@inheritDoc}
     */
    protected function getCacheKey($id)
    {
        return sprintf('vimeo_video_service_%s', $id);
    }

    /**
     * {@inheritDoc}
     */
    protected function getAvailableMetadata()
    {
        return self::$AVAILABLE_METADATA;
    }

    /**
     * {@inheritDoc}
     */
    protected function loadMetaValue($name, $default = NULL)
    {
        if(!in_array($name, self::$AVAILABLE_METADATA))
            return $default;

        $query = sprintf(self::XPATH_SCHEME, $name);
        $elements = $this->xpath->query($query);

        switch($name)
        {
            case 'description':
                if (!is_null($elements) && $elements->length > 0)
                    return strip_tags($elements->item(0)->nodeValue);
                break;

            case 'tags':
                if(!is_null($elements) && $elements->length > 0)
                    return explode(", ", $elements->item(0)->nodeValue);
                break;

            default:
                if (!is_null($elements) && $elements->length > 0)
                    return $elements->item(0)->nodeValue;
        }

        return $default;
    }

    /**
     * {@inheritDoc}
     */
    protected function getResponse($id, $options = array())
    {
        $requestUrl = sprintf(self::API_URL, $id);

        /**
         * @var \Buzz\Message\Response $response
         */
        $response = $this->buzz->get($requestUrl);

        if($response->isClientError() || $response->isServerError())
            throw new ResourceNotFoundException(sprintf('Cannot find youtube video with id "%s"', $id), $id);

        return $response->getContent();
    }

    /**
     * {@inheritDoc}
     */
    protected function afterLoad()
    {
        $this->document = new \DOMDocument();
        $this->document->loadXML($this->response);
        $this->xpath = new \DOMXpath($this->document);
    }

}
