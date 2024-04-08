<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.6
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\thirdparty\inc\plugin;

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
	 * @param void
	 * @return bool
	 */
	public static function isEnabled()
	{
		return defined('WPO_VERSION');
	}
	
	/**
	 * Purge cache.
	 * 
	 * @access public
	 * @param void
	 * @return bool
	 * @internal
	 */
	public static function purge()
	{
		if ( class_exists('\WP_Optimize_Cache_Commands') ) {
            $wpoptimize = new \WP_Optimize_Cache_Commands();
            $wpoptimize->purge_page_cache();
            return true;
		}
		return false;
	}
}