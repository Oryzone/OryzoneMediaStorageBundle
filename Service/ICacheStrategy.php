<?php

namespace Oryzone\Bundle\MediaStorageBundle\Service;

/**
 * Simple interface for cache strategy classes
 */
interface ICacheStrategy
{

	/**
	 * Gets the value for a given entry id
	 *
	 * @abstract
	 * @param string $id
	 * @return string
	 */
	public function get($id);

	/**
	 * Checks if an entry has been stored into the cache
	 *
	 * @abstract
	 * @param string $id
	 * @return bool
	 */
	public function has($id);

	/**
	 * Store a new entry into the cache
	 *
	 * @param string $id
	 * @param string $path
	 * @return string
	 */
	public function set($id, $path);

}
