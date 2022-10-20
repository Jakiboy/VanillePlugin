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

namespace VanillePlugin\inc;

final class Json extends File
{
	/**
	 * Parse JSON file.
	 *
	 * @access public
	 * @param bool $isArray
	 * @return mixed
	 */
	public static function parse($file, $isArray = false)
	{
		return self::decode(self::r($file),$isArray);
	}

	/**
	 * Decode JSON.
	 *
	 * @access public
	 * @param string $content
	 * @param bool $isArray
	 * @return mixed
	 */
	public static function decode($content, $isArray = false)
	{
		return json_decode((string)$content,(bool)$isArray);
	}

	/**
	 * Encode JSON.
	 *
	 * @access public
	 * @param mixen $data
	 * @return string
	 */
	public static function encode($data)
	{
		return json_encode($data);
	}

	/**
	 * Format JSON For WordPress.
	 *
	 * @access public
	 * @param mixen $data
	 * @param int $args
	 * @return string
	 *
	 * JSON_UNESCAPED_UNICODE : 256
	 * JSON_PRETTY_PRINT : 128
	 * JSON_UNESCAPED_SLASHES : 64
	 */
	public static function format($data, $args = 64|256)
	{
		return json_encode($data,$args);
	}
}
