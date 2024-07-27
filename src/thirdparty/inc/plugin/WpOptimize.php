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
 * WP-Optimize plugin helper class.
 *
 * @see https://github.com/DavidAnderson684/WP-Optimize
 */
final class WpOptimize
{
	/**
	 * Check whether plugin is enabled.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isEnabled() : bool
	{
		return Helper::isClass('\WP_Optimize_Cache_Commands');
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
            $wpoptimize = new \WP_Optimize_Cache_Commands();
			$result = $wpoptimize->purge_page_cache();
            return $result['success'];
		}
		return false;
	}
}
