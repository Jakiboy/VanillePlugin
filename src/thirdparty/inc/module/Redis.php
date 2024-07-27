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

namespace VanillePlugin\thirdparty\inc\module;

use VanillePlugin\thirdparty\Helper;

/**
 * Redis module helper class.
 *
 * @see https://github.com/phpredis/phpredis
 */
final class Redis
{
	/**
	 * Check module is enabled.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isEnabled() : bool
	{
		return Helper::isClass('\Redis');
	}

	/**
	 * Purge cache.
	 *
	 * @access public
	 * @return bool
	 */
	public static function purge() : bool
	{
		return false;
	}
}
