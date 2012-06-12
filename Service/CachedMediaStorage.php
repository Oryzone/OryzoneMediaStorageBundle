<?php

namespace Oryzone\Bundle\MediaStorageBundle\Service;

use Oryzone\Bundle\MediaStorageBundle\Service\Cache\InMemoryCacheStrategy;

class CachedMediaStorage extends MediaStorage
{
	/**
	 * The original media storage used to perform requests
	 *
	 * @var MediaStorageInterface $originalMediaStorage
	 */
	protected $originalMediaStorage;

	/**
	 * The object used as cache storage
	 *
	 * @var CacheStrategyInterface
	 */
	protected $cache;

	/**
	 * The number of times an item path is retrieved from cache. Mostly useful for debugging
	 *
	 * @var int
	 */
	protected $cacheHits;

    protected $stored;

    protected $located;

	/**
	 * Constructor
	 *
	 * @param MediaStorageInterface $originalMediaStorage the original media storage used to perform requests
	 * @param null|CacheStrategyInterface $cache the cache engine to use
	 */
	public function __construct(MediaStorageInterface $originalMediaStorage, CacheStrategyInterface $cache = NULL)
	{
		$this->originalMediaStorage = $originalMediaStorage;

		if($cache === NULL)
			$cache = new InMemoryCacheStrategy();

		$this->cache = $cache;

		$this->cacheHits = 0;
        $this->stored = array();
        $this->located = array();
	}

	/**
	 * Calculate a unique hash string for each request
	 *
	 * @param $id
	 * @param $name
	 * @param $type
	 * @param $variant
	 * @return string
	 */
	protected function getHash($id, $name, $type, $variant)
	{
		return md5(sprintf('i:%s,n:%s,t:%s,v:%s,%s', $id, $name, $type, $variant, $this->getStorageStateId()));
	}

	/**
	 * {@inheritDoc}
	 */
	public function locate($id, $name, $type, $variant = NULL, $fallbackToDefaultVariant = true)
	{
		$hash = $this->getHash($id, $name, $type, $variant);

		if( $this->cache->has($hash) )
		{
			$this->cacheHits++;
            $this->located[$hash]['hits']++;
			return $this->cache->get($hash);
		}

		$path = $this->originalMediaStorage->locate($id, $name, $type, $variant, $fallbackToDefaultVariant);
		$this->cache->set($hash, $path);
        $this->located[$hash] = array(
            'id'    => $id,
            'name'  => $name,
            'type'  => $type,
            'variant' => $variant,
            'path' => $path,
            'hits' => 1
        );

		return $path;
	}

	/**
	 * {@inheritDoc}
	 */
	public function store($sourceFile, $id, $name, $type, $variant = NULL)
	{
        $this->stored[] = array(
            'id'    => $id,
            'name'  => $name,
            'type'  => $type,
            'variant' => $variant,
            'source' => $sourceFile
        );
        return $this->originalMediaStorage->store($sourceFile, $id, $name, $type, $variant);
	}

	/**
	 * Gets the currently set cache strategy
	 *
	 * @return CacheStrategyInterface
	 */
	public function getCache()
	{
		return $this->cache;
	}

    /**
     * Get located files
     *
     * @return array
     */
    public function getLocated()
    {
        return $this->located;
    }

    /**
     * Get stored files
     *
     * @return array
     */
    public function getStored()
    {
        return $this->stored;
    }

	/**
	 * Gets the cache hits count
	 *
	 * @return int
	 */
	public function getCacheHits()
	{
		return $this->cacheHits;
	}

	/**
	 * Gets the original media storage
	 *
	 * @return MediaStorageInterface
	 */
	public function getOriginalMediaStorage()
	{
		return $this->originalMediaStorage;
	}

	/**
	 * Sets the original media storage to use
	 *
	 * @param MediaStorageInterface $originalMediaStorage
	 */
	public function setOriginalMediaStorage(MediaStorageInterface $originalMediaStorage)
	{
		$this->originalMediaStorage = $originalMediaStorage;
	}

}
