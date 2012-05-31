<?php

namespace Oryzone\Bundle\MediaStorageBundle\Service\Cache;

use Oryzone\Bundle\MediaStorageBundle\Service\ICacheStrategy;

class InMemoryCacheStrategy implements ICacheStrategy
{

	/**
	 * An array used to store data in memory
	 *
	 * @var array
	 */
	protected $cache;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->cache = array();
	}

	/**
	 * {@inheritDoc}
	 */
	public function get($id)
	{
		return $this->cache[$id];
	}

	/**
	 * {@inheritDoc}
	 */
	public function has($id)
	{
		return isset($this->cache[$id]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function set($id, $path)
	{
		$this->cache[$id] = $path;
	}
}
