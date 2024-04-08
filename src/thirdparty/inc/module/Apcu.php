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

namespace VanillePlugin\thirdparty\inc\module;

/**
 * APCu module helper class.
 * 
 * @see https://www.php.net/manual/en/function.apcu-clear-cache.php
 */
final class Apcu
{
	/**
	 * Check module plugin is enabled.
	 * 
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function isEnabled()
	{
		return function_exists('apcu_clear_cache');
	}

	/**
	 * Purge cache.
	 * 
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function purge()
	{
		return apcu_clear_cache();
	}
}