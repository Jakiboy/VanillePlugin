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
 * WPML plugin helper class.
 *
 * @see https://github.com/wp-premium/wpml-media
 */
final class Wpml
{
	/**
	 * Check whether plugin is enabled.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isEnabled() : bool
	{
		return Helper::isClass('\SitePress');
	}

	/**
	 * Check whether plugin is active.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isActive() : bool
	{
		if ( self::isEnabled() ) {
			return (bool)self::getLanguages();
		}
		return false;
	}

	/**
	 * Get current locale.
	 *
	 * @access public
	 * @return mixed
	 */
	public static function getLocale()
	{
		$locale = Helper::applyFilter('wpml_current_language', null);
		if ( $locale == 'all' ) {
			$locale = false;
		}
		return $locale;
	}

	/**
	 * Get active languages.
	 *
	 * @access public
	 * @return mixed
	 */
	public static function getLanguages()
	{
		global $sitepress;
		if ( Helper::hasMethod($sitepress, 'get_active_languages') ) {
			return Helper::keys(
				(array)$sitepress->get_active_languages()
			);
		}
		return false;
	}
}
