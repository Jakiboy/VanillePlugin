<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.0
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\thirdparty\inc\plugin;

final class WpFastestCache
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
		return defined('LSCWP_BASENAME');
	}
	
	/**
	 * Purge cache.
	 * 
	 * @access public
	 * @param void
	 * @return bool (internal)
	 * @see https://github.com/emrevona/wp-fastest-cache
	 */
	public static function purge()
	{
		if ( method_exists('\WpFastestCache','deleteCache') ) {
			$cache = new \WpFastestCache();
			$cache->deleteCache();
			return true;
		}
		return false;
	}
}
