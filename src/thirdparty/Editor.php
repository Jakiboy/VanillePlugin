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

/**
 * Third-Party editor helper class.
 */
final class Editor
{
	/**
	 * Check whether editor is classic.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isClassic() : bool
	{
		return Helper::isClass('\Classic_Editor');
	}

	/**
	 * Check whether editor is gutenberg.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isGutenberg() : bool
	{
		if ( Helper::hasFilter('replace_editor', 'gutenberg_init') ) {
			return true;
			
		} elseif ( !self::isClassic() ) {
			return true;
		}

		return false;
	}
}
