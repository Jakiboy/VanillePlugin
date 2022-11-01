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

namespace VanillePlugin\thirdparty\inc\module;

/**
 * OpCache Helper Class.
 * 
 * @see https://www.php.net/manual/en/book.opcache.php
 */
final class OpCache
{
	/**
	 * Purge cache.
	 * 
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function purge()
	{
		if ( function_exists('opcache_reset') ) {
			return opcache_reset();
		}
		return false;
	}
}