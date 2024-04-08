<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.6
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\thirdparty\inc\plugin;

/**
 * QTranslate plugin helper class.
 * 
 * @see https://github.com/qtranslate/qtranslate-xt
 */
final class Qtranslate
{
	/**
	 * Check whether plugin is enabled.
	 * 
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function isEnabled()
	{
		return defined('QTX_VERSION');
	}

	/**
	 * Check whether plugin is active (functional).
	 * 
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function isActive()
	{
		global $q_config;
		if ( !empty($q_config) && is_array($q_config) ) {
			if ( function_exists('qtranxf_convertURL') ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get active languages.
	 * 
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public static function getActiveLanguages()
	{
		return $GLOBALS['q_config']['enabled_languages'] ?? false;
	}

	/**
	 * Get current locale.
	 * 
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public static function getLocale()
	{
		return false;
	}
}