<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\tr;

use VanillePlugin\inc\{
	Transient, Cache
};
use VanillePlugin\lib\Orm;

/**
 * Define caching functions.
 */
trait TraitCacheable
{
	/**
	 * Get cache value.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getCache(string $key, ?bool &$status = null, ?string $group = null)
	{
		return Cache::get($key, (string)$group, false, $status);
	}

	/**
	 * Get transient.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getTransient(string $key)
	{
		return Transient::get($key);
	}

	/**
	 * Get site transient.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getSiteTransient(string $key)
	{
		return Transient::getSite($key);
	}

	/**
	 * Set cache value.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function setCache(string $key, $value, int $ttl = 0, ?string $group = null) : bool
	{
		return Cache::set($key, $value, $ttl, (string)$group);
	}

	/**
	 * Add value to cache.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addCache(string $key, $value, int $ttl = 0, ?string $group = null) : bool
	{
		return Cache::add($key, $value, $ttl, (string)$group);
	}

	/**
	 * Update cache value.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function updateCache(string $key, $value, int $ttl = 0, ?string $group = null) : bool
	{
		return Cache::update($key, $value, $ttl, (string)$group);
	}

	/**
	 * Delete cache.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function deleteCache(string $key, ?string $group = null) : bool
	{
		return Cache::delete($key, (string)$group);
	}

	/**
	 * Purge cache.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function purgeCache() : bool
	{
		return Cache::purge();
	}

	/**
	 * Set transient.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function setTransient(string $key, $value = 1, int $ttl = 300) : bool
	{
		return Transient::set($key, $value, $ttl);
	}

	/**
	 * Set site transient.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function setSiteTransient(string $key, $value = 1, int $ttl = 300) : bool
	{
		return Transient::setSite($key, $value, $ttl);
	}

	/**
	 * Delete transient.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function deleteTransient(string $key) : bool
	{
		return Transient::delete($key);
	}

	/**
	 * Delete site transient.
	 * 
	 * @access protected
	 * @inheritdoc
	 */
	protected function deleteSiteTransient(string $key) : bool
	{
		return Transient::deleteSite($key);
	}

	/**
	 * Remove transients.
	 * Under namespace.
	 *
	 * @access protected
	 * @param string $namespace
	 * @return bool
	 */
	protected function removeTransients(string $namespace) : bool
	{
		if ( empty($namespace) ) {
			return false;
		}

		$db   = new Orm();
		$sql  = "DELETE FROM `{$db->prefix}options` ";
		$sql .= "WHERE `option_name` LIKE '%_transient_{$namespace}%';";

		return (bool)$db->execute($sql);
	}

	/**
	 * Remove site transients.
	 * Under namespace.
	 *
	 * @access protected
	 * @param string $namespace
	 * @return bool
	 */
	protected function removeSiteTransients(string $namespace) : bool
	{
		if ( empty($namespace) ) {
			return false;
		}

		$db   = new Orm();
		$sql  = "DELETE FROM {$db->prefix}options ";
		$sql .= "WHERE `option_name` LIKE '%_site_transient_{$namespace}%';";

		return (bool)$db->execute($sql);
	}
}
