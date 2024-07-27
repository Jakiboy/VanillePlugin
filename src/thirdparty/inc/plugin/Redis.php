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

namespace VanillePlugin\thirdparty\inc\plugin;

use VanillePlugin\thirdparty\Helper;

/**
 * Redis Object Cache plugin helper class.
 *
 * @see https://github.com/rhubarbgroup/redis-cache
 */
final class Redis
{
	/**
	 * Check whether plugin is enabled.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isEnabled() : bool
	{
		return Helper::isClass('\Rhubarb\RedisCache\Plugin');
	}

	/**
	 * Purge cache.
	 *
	 * @access public
	 * @return bool
	 * @internal
	 */
	public static function purge() : bool
	{
		if ( self::isEnabled() ) {
			global $wp_object_cache;
			if ( Helper::hasMethod($wp_object_cache, 'redis_instance') ) {
				$wp_object_cache->redis_instance()->flushall();
				return true;
			}
		}
		return false;
	}
}
