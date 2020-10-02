<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.2
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

class File
{
	/**
	 * read file
	 *
	 * @param string $file
	 * @return string
	 */
	public static function read($path)
	{
		return file_get_contents($path);
	}

	/**
	 * write file
	 *
	 * @param string $file
	 * @param string $content
	 * @return void
	 */
	public static function write($path, $content)
	{
		file_put_contents($path, $content);
	}

	/**
	 * write file
	 *
	 * @param string $file
	 * @param string $content
	 * @return void
	 */
	public static function append($path, $content)
	{
		file_put_contents($path, $content, FILE_APPEND);
	}

	/**
	 * file exists
	 *
	 * @param string $file
	 * @return boolean
	 */
	public static function exists($path)
	{
		return file_exists($path);
	}
}
