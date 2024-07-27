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
 * AMP plugin helper class.
 *
 * @see https://github.com/ampproject/amp-wp/
 * @see https://github.com/ahmedkaludi/Accelerated-Mobile-Pages
 */
final class Amp
{
	/**
	 * Check whether plugin is enabled.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isEnabled() : bool
	{
		if ( Helper::isFunction('amp_is_enabled') ) {
			return true;
		}

		if ( Helper::isFunction('ampforwp_init') ) {
			return true;
		}
		
		return false;
	}

	/**
	 * Check whether plugin is active.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isActive() : bool
	{
		if ( Helper::isFunction('amp_is_request') ) {
			return amp_is_request();
		}

		if ( Helper::isFunction('ampforwp_is_amp_endpoint') ) {
			return ampforwp_is_amp_endpoint();
		}
		
		return false;
	}
}
