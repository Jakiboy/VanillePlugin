<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

final class HttpGet
{
	/**
	 * Get _GET value.
	 *
	 * @access public
	 * @param string $key
	 * @return mixed
	 */
	public static function get(?string $key = null)
	{
		if ( $key ) {
			return self::isSetted($key) ? $_GET[$key] : null;
		}
		return self::isSetted() ? $_GET : null;
	}

	/**
	 * Set _GET value.
	 *
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @return void
	 */
	public static function set(string $key, $value = null)
	{
		$_GET[$key] = $value;
	}
	
	/**
	 * Check _GET value.
	 *
	 * @access public
	 * @param string $key
	 * @return bool
	 */
	public static function isSetted(?string $key = null) : bool
	{
		if ( $key ) {
			return isset($_GET[$key]);
		}
		return isset($_GET) && !empty($_GET);
	}

	/**
	 * Unset _GET value.
	 *
	 * @access public
	 * @param string $key
	 * @return void
	 */
	public static function unset(?string $key = null)
	{
		if ( $key ) {
			unset($_GET[$key]);

		} else {
			$_GET = [];
		}
	}
}
