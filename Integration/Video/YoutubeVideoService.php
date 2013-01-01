<?php

namespace Oryzone\Bundle\MediaStorageBundle\Integration\Video;

use Oryzone\Bundle\MediaStorageBundle\Exception\Integration\ResourceNotFoundException;

class YoutubeVideoService extends VideoService
{
    /**
     * Url scheme for the API requests
     */
    const API_URL = 'http://gdata.youtube.com/feeds/api/videos/%s';

    /**
     * Url scheme for the video preview image
     * @const string PREVIEW_IMAGE_URL
     */
    const PREVIEW_IMAGE_URL = 'http://img.youtube.com/vi/%s/0.jpg';

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
     * Xpaths used to extract data from response
     * @var array $XPATHS
     */
    protected static $XPATHS = array(
        'id'            =>  '/a:entry/a:id',
        'published'     =>  '/a:entry/a:published',
        'updated'       =>  '/a:entry/a:updated',
        'title'         =>  '/a:entry/a:title',
        'content'       =>  '/a:entry/a:content',
        'tags'          =>  '(/a:entry/a:category/@term)[position()>1]',
    );

    /**
     * {@inheritDoc}
     */
    protected function getCacheKey($id)
    {
        return sprintf('youtube_video_service_%s', $id);
    }

    /**
     * {@inheritDoc}
     */
    protected function getAvailableMetadata()
    {
        return array(
            'id',
            'published',
            'updated',
            'title',
            'content',
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function loadMetaValue($name, $default = NULL)
    {
        switch($name)
        {
            case 'thumbnail':
                return sprintf(self::PREVIEW_IMAGE_URL, $this->lastId);
                break;

            case 'tags':
                $elements = $this->xpath->query(self::$XPATHS[$name]);
                if(!is_null($elements) && $elements->length > 0)
                {
                    $tags = array();
                    foreach($elements as $element)
                        $tags[] = $element->nodeValue;
                    return $tags;
                }
                break;

            default:
                if(!isset(self::$XPATHS[$name]))
                    return $default;

                $elements = $this->xpath->query(self::$XPATHS[$name]);

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
        $this->xpath->registerNamespace('a', 'http://www.w3.org/2005/Atom');
    }
}
