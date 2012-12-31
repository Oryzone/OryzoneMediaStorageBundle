<?php

namespace Oryzone\Bundle\MediaStorageBundle\Integration\Video;

use Buzz\Browser;

use Doctrine\Common\Cache\Cache;

abstract class VideoService implements VideoServiceInterface
{
    /**
     * The array of the default options for the load method
     *
     * @var array $DEFAULT_OPTIONS
     */
    protected static $DEFAULT_OPTIONS = array(
        'cache' => TRUE,
        'cacheLifetime' => 604800 // a week
    );

    /**
     * The http client library used to issue requests
     *
     * @var \Buzz\Browser
     */
    protected $buzz;

    /**
     * Cache layer
     *
     * @var \Doctrine\Common\Cache\Cache $cache
     */
    protected $cache;

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
     * The last loaded id
     * @var string $lastId
     */
    protected $lastId;

    /**
     * Constructor
     *
     * @param \Buzz\Browser $buzz
     * @param \Doctrine\Common\Cache\Cache $cache
     */
    public function __construct(Browser $buzz, Cache $cache = NULL)
    {
        $this->buzz = $buzz;
        $this->cache = $cache;
    }

    /**
     * Gets the cache key for a given id
     *
     * @param string $id
     * @return string
     */
    abstract protected function getCacheKey($id);

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
     * Called automatically after the load method
     *
     * @return void
     */
    abstract protected function afterLoad();

    /**
     * {@inheritDoc}
     */
    public function load($id, $options = array())
    {
        $this->lastId = $id;
        $options = array_merge(static::$DEFAULT_OPTIONS, $options);
        $cacheKey = $this->getCacheKey($id);

        $this->metadata = array();

        if($this->cache !== NULL && $options['cache'])
        {
            if($this->cache->contains($cacheKey))
                $this->response = $this->cache->fetch($cacheKey);
            else
            {
                $this->response = $this->getResponse($id, $options);
                $this->cache->save($cacheKey, $this->response, $options['cacheLifetime']);
            }
        }
        else
            $this->response = $this->getResponse($id, $options);

        $this->afterLoad();
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
