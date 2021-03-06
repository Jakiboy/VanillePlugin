<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.6.8
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

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
		if ( function_exists('is_amp_endpoint') ) {
			return is_amp_endpoint();
		} else {
			return false;
		}
	}
}
