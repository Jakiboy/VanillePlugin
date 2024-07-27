<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 0.9.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\inc\{
    File, Stringify, TypeCheck
};

/**
 * Plugin cache manager.
 */
final class Cache extends PluginOptions
{
	/**
	 * @access public
	 * @var string INTERNAL, Internal cache
	 * @var string THIRD, Third-Party cache
	 */
	public const INTERNAL = '\VanilleCache\Cache';
	public const THIRD    = '\VanillePlugin\thirdparty\Cache';

	/**
	 * Get cache value.
	 *
	 * @access public
	 * @param string $key
	 * @param string $group
	 * @param bool $found
	 * @return mixed
	 */
	public function get(string $key, ?bool &$status = null, ?string $group = null)
	{
		$key = $this->formatKey($key);
		if ( $group ) {
			$group = $this->formatKey($group);
		}
		return wp_cache_get($key, $group, false, $status);
	}

	/**
	 * Set cache value.
	 *
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @param int $ttl
	 * @param string $group
	 * @return bool
	 */
	public function set(string $key, $value, ?int $ttl = null, ?string $group = null) : bool
	{
		if ( TypeCheck::isNull($ttl) ) {
			$ttl = $this->getExpireIn();
		}
		$key = $this->formatKey($key);
		if ( $group ) {
			$group = $this->formatKey($group);
		}
		return wp_cache_set($key, $value, $group, $ttl);
	}

	/**
	 * Add value to cache.
	 *
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @param int $ttl
	 * @param string $group
	 * @return bool
	 */
	public function add(string $key, $value, ?int $ttl = null, ?string $group = null) : bool
	{
		if ( TypeCheck::isNull($ttl) ) {
			$ttl = $this->getExpireIn();
		}
		$key = $this->formatKey($key);
		if ( $group ) {
			$group = $this->formatKey($group);
		}
		return wp_cache_add($key, $value, $group, $ttl);
	}

	/**
	 * Update cache value.
	 *
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @param int $ttl
	 * @param string $group
	 * @return bool
	 */
	public function update(string $key, $value, ?int $ttl = null, ?string $group = null) : bool
	{
		if ( TypeCheck::isNull($ttl) ) {
			$ttl = $this->getExpireIn();
		}
		$key = $this->formatKey($key);
		if ( $group ) {
			$group = $this->formatKey($group);
		}
		return wp_cache_replace($key, $value, $group, $ttl);
	}

	/**
	 * Delete cache.
	 *
	 * @access public
	 * @param string $key
	 * @param string $group
	 * @return bool
	 */
	public function delete(string $key, ?string $group = null)
	{
		$key = $this->formatKey($key);
		if ( $group ) {
			$group = $this->formatKey($group);
		}
		return wp_cache_delete($key, $group);
	}

	/**
	 * Purge any cache.
	 *
	 * @access public
	 * @param bool $force
	 * @return bool
	 */
	public function purge(bool $force = false) : bool
	{
		$count = 0;
		$count += (int)wp_cache_flush();

		if ( $this->hasThirdCache() ) {
			$cache  = self::THIRD;
			$count += (int)$cache::purge();
		}

		if ( $this->hasInternalCache() ) {
			$cache  = self::getInternalCache();
			$count += (int)$cache->purge();
		}

		if ( $force ) {
			$path[] = $this->getTempPath();
			$path[] = $this->getCachePath();
			foreach ($path as $path) {
				$count += (int)File::clearDir($path);
			}
		}

		return (bool)$count;
	}

	/**
	 * Check third-party cache.
	 *
	 * @access public
	 * @return bool
	 */
	public function hasThirdCache() : bool
	{
		if ( TypeCheck::isClass(self::THIRD) ) {
			$cache = self::THIRD;
			if ( TypeCheck::hasMethod($cache, 'isActive') ) {
				return $cache::isActive();
			}
		}
		return false;
	}

	/**
	 * Check internal cache.
	 *
	 * @access public
	 * @return bool
	 */
	public function hasInternalCache() : bool
	{
		return TypeCheck::isClass(self::INTERNAL);
	}

	/**
	 * Get internal cache.
	 *
	 * @access public
	 * @param string $driver
	 * @return object
	 */
	public function getInternalCache(string $driver = 'File') : object
	{
		$cache = self::INTERNAL;
		return new $cache($driver);
	}

	/**
	 * Format cache key.
	 *
	 * @access private
	 * @param string $key
	 * @return string
	 */
	private function formatKey(string $key) : string
	{
		$key = Stringify::sanitizeKey($key);
		return $this->applyNamespace($key);
	}
}
