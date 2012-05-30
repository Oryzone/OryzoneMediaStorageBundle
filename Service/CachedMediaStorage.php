<?php

namespace Oryzone\Bundle\MediaStorageBundle\Service;

use Oryzone\Bundle\MediaStorageBundle\Service\Cache\InMemoryCacheStrategy;

class CachedMediaStorage extends AbstractMediaStorage
{

	/**
	 * The original media storage used to perform requests
	 *
	 * @var IMediaStorage $originalMediaStorage
	 */
	protected $originalMediaStorage;

	/**
	 * The object used as cache storage
	 *
	 * @var ICacheStrategy
	 */
	protected $cache;

	/**
	 * The number of times an item path is retrieved from cache. Mostly useful for debugging
	 *
	 * @var int
	 */
	protected $cacheHits;

	/**
	 * Constructor
	 *
	 * @param IMediaStorage $originalMediaStorage the original media storage used to perform requests
	 * @param null|ICacheStrategy $cache the cache engine to use
	 */
	public function __construct(IMediaStorage $originalMediaStorage, ICacheStrategy $cache = NULL)
	{
		$this->originalMediaStorage = $originalMediaStorage;

		if($cache === NULL)
			$cache = new InMemoryCacheStrategy();

		$this->cache = $cache;

		$this->cacheHits = 0;
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
		return md5(sprintf('i:%s,n:%s,t:%s,v:%s,%s', $id, $name, $type, $variant, $this->getSerializedConfiguration()));
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
			return $this->cache->get($hash);
		}

		$path = $this->originalMediaStorage->locate($id, $name, $type, $variant, $fallbackToDefaultVariant);
		$this->cache->set($hash, $path);

		return $path;
	}

	/**
	 * {@inheritDoc}
	 */
	public function store($sourceFile, $id, $name, $type, $variant = NULL)
	{
		return $this->originalMediaStorage->store($sourceFile, $id, $name, $type, $variant);
	}

	/**
	 * Gets the currently set cache strategy
	 *
	 * @return ICacheStrategy
	 */
	public function getCache()
	{
		return $this->cache;
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
	 * @return IMediaStorage
	 */
	public function getOriginalMediaStorage()
	{
		return $this->originalMediaStorage;
	}

	/**
	 * Sets the original media storage to use
	 *
	 * @param IMediaStorage $originalMediaStorage
	 */
	public function setOriginalMediaStorage(IMediaStorage $originalMediaStorage)
	{
		$this->originalMediaStorage = $originalMediaStorage;
	}

}
