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

namespace VanillePlugin\thirdparty;

use VanillePlugin\thirdparty\inc\plugin\Amp as Plugin;

/**
 * Third-Party AMP helper class.
 */
final class Amp
{
	/**
	 * Check whether AMP plugin is enabled.
	 * [Action: ini].
	 *
	 * @access public
	 * @return bool
	 */
	public static function isEnabled() : bool
	{
		return Plugin::isEnabled();
	}

	/**
	 * Check whether AMP is active.
	 * [Action: wp].
	 * [Action: wp_head].
	 *
	 * @access public
	 * @return bool
	 */
	public static function isActive() : bool
	{
		return Plugin::isActive();
	}
}
