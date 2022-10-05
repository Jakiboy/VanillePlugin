<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.9
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

namespace VanillePlugin\inc;

final class Filesystem extends File
{
	/**
	 * @access public
	 * @param array $dirs
	 * @return void
	 */
	public static function create($dirs = [])
	{
		foreach ($dirs as $dir) {
	        if ( !self::exists($dir) ) {
	            self::addDir($dir);
	        }
		}
	}
}
