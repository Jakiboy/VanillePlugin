<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.6
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePluginTest\inc;

final class PostTest
{
	/**
	 * @access public
	 * @param string $item null
	 * @return mixed
	 */
	public static function get($item = null)
	{
		if ($item) {
			return self::isSetted($item) ? $_POST[$item] : false;
		} else {
			return $_POST;
		}
	}

	/**
	 * @access public
	 * @param string $item
	 * @param mixed $value
	 * @return void
	 */
	public static function set($item, $value)
	{
		$_POST[$item] = $value;
	}

	/**
	 * @access public
	 * @param string $item null
	 * @return boolean
	 */
	public static function isSetted($item = null)
	{
		if ($item) {
			return isset($_POST[$item]);
		} else {
			return isset($_POST);
		}
	}
}
