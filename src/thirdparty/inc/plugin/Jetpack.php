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
 * Jetpack boost plugin helper class.
 *
 * @see https://github.com/rhubarbgroup/redis-cache
 */
final class Jetpack
{
	/**
	 * Check whether plugin is enabled.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isEnabled() : bool
	{
		return Helper::isFunction('jetpack_boost_page_optimize_cache_cleanup');
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
			jetpack_boost_page_optimize_cache_cleanup();
            return true;
		}
		return false;
	}
}
