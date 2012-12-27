<?php

namespace Oryzone\Bundle\MediaStorageBundle\Integration\Video;

use Buzz\Browser;

abstract class VideoService implements VideoServiceInterface
{
    /**
     * The http client library used to issue requests
     *
     * @var \Buzz\Browser
     */
    protected $buzz;

    /**
     * The metadata array
     *
     * @var array $metadata
     */
    protected $metadata;

    /**
     * @var string $response
     */
    protected $response;

    /**
     * Constructor
     *
     * @param \Buzz\Browser $buzz
     */
    public function __construct(Browser $buzz)
    {
        $this->buzz = $buzz;
    }

    /**
     * Gets an array of the commonly available metadata keys for the service
     *
     * @return array
     */
    abstract protected function getAvailableMetadata();

    /**
     * Method that implements the logic to extract a given metadata from the raw response
     *
     * @param $name
     * @param null $default
     * @return mixed
     */
    abstract protected function loadMetaValue($name, $default = NULL);

    /**
     * Implements the logic to issue the request to the service
     *
     * @param string $id
     * @param array $options
     * @return string
     */
    abstract protected function getResponse($id, $options = array());

    /**
     * {@inheritDoc}
     */
    public function load($id, $options = array())
    {
        $this->metadata = array();
        $this->response = $this->getResponse($id, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function getMetadata()
    {
        //ensures all the metadata to be loaded (forces lazy loading)
        foreach(($keys = $this->getAvailableMetadata()) as $key)
            $this->getMetaValue($key);

        return $this->metadata;
    }

    /**
     * {@inheritDoc}
     */
    public function getMetaValue($name, $default = NULL)
    {
        // Each metadata value is lazy loaded and memory cached
        if(isset($this->metadata[$name]))
            return $this->metadata[$name];

        $this->metadata[$name] = $this->loadMetaValue($name, $default);
        return $this->metadata[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function getRawResponse()
    {
        return $this->response;
    }
}
