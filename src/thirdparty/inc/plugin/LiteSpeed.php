<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.1
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\thirdparty\inc\plugin;

/**
 * LiteSpeed Helper Class.
 * 
 * @see https://github.com/litespeedtech/lscache_wp
 */
final class LiteSpeed
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
	 * @return bool
	 * @internal
	 */
	public static function purge()
	{
		if ( class_exists('\LiteSpeed\Purge') ) {
			\LiteSpeed\Purge::purge_all();
			return true;
		}
		return false;
	}
}
