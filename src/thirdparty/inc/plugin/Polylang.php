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
 * Polylang plugin helper class.
 *
 * @see https://github.com/polylang/polylang
 */
final class Polylang
{
	/**
	 * Check whether plugin is enabled.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isEnabled() : bool
	{
		return Helper::isClass('\Polylang');
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
		if ( ($locale = self::getLocalById()) ) {
			return $locale;
		}
		return self::getLocaleByUrl();
	}
	
	/**
	 * Get active languages.
	 *
	 * @access public
	 * @return mixed
	 */
	public static function getLanguages()
	{
		if ( Helper::isFunction('pll_languages_list') ) {
			return pll_languages_list();
		}
		return false;
	}

	/**
	 * Get locale by Id.
	 *
	 * @access public
	 * @return mixed
	 */
	public static function getLocalById()
	{
		if ( Helper::isFunction('pll_get_post_language') ) {
			if ( ($id = Helper::getRefererId()) && !Helper::isAdmin() ) {
				if ( ($locale = pll_get_post_language($id, 'locale')) ) {
					return $locale;
				}
			}
		}
		return false;
	}

	/**
	 * Get locale by URL.
	 *
	 * @access public
	 * @return mixed
	 */
	public static function getLocaleByUrl()
	{
		if ( Helper::isFunction('pll_current_language') ) {
			return pll_current_language('locale');
		}
		return false;
	}
}
