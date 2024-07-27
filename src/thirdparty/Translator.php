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
 * Third-Party translator helper class.
 */
final class Translator
{
	/**
	 * @access public
	 */
	public const PLUGINS = [
		'Polylang',
		'Wpml'
	];

	/**
	 * Check whether translator is active.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isActive() : bool
	{
		foreach (self::PLUGINS as $plugn) {
			$plugn = __NAMESPACE__ . "\\inc\\plugin\\{$plugn}";
			if ( $plugn::isActive() ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get translator active languages.
	 *
	 * @access public
	 * @return mixed
	 */
	public static function getLanguages()
	{
		foreach (self::PLUGINS as $plugn) {
			$plugn = __NAMESPACE__ . "\\inc\\plugin\\{$plugn}";
			if ( $plugn::isActive() ) {
				return $plugn::getLanguages();
			}
		}
		return false;
	}

	/**
	 * Get translator locale.
	 * [Action: head].
	 * [Action: admin_init].
	 *
	 * @access public
	 * @return mixed
	 */
	public static function getLocale()
	{
		foreach (self::PLUGINS as $plugn) {
			$plugn = __NAMESPACE__ . "\\inc\\plugin\\{$plugn}";
			if ( $plugn::isActive() ) {
				return $plugn::getLocale();
			}
		}
		return false;
	}
}
