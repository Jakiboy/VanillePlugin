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

namespace VanillePlugin\thirdparty;

final class AMP
{
	/**
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function isActive()
	{
		/**
		 * Check AMP.
		 * 
		 * @see https://github.com/ampproject/amp-wp/
		 */
		if ( function_exists('amp_is_request') ) {
			return amp_is_request();
		}
		return false;
	}
}
