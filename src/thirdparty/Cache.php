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
 * Third-Party cache helper class.
 */
final class Cache
{
	/**
	 * @access public
	 */
	public const MODULES = [
		'Opcache',
		'Apcu',
		'Memcached'
	];
	public const PLUGINS = [
		'Redis',
		'LiteSpeed',
		'WpRocket',
		'W3TotalCache',
		'Kinsta',
		'WpOptimize',
		'WpFastestCache',
		'WpSuperCache',
		'CacheEnabler',
		'NginxHelper',
		'Cloudflare',
		'SpeedOptimizer',
		'SpeedyCache',
		'Autoptimize',
		'Jetpack'
	];

	/**
	 * Check whether cache is active.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isActive() : bool
	{
		foreach (self::PLUGINS as $plugn) {
			$plugn = __NAMESPACE__ . "\\inc\\plugin\\{$plugn}";
			if ( $plugn::isEnabled() ) {
				return true;
			}
		}

		foreach (self::MODULES as $module) {
			$module = __NAMESPACE__ . "\\inc\\module\\{$module}";
			if ( $module::isEnabled() ) {
				return true;
			}
		}

		return Helper::hasCache();
	}

	/**
	 * Purge plugin cache including server cache.
	 *
	 * @access public
	 * @return void
	 */
	public static function purge() : bool
	{
		if ( !self::isActive() ) {
			return false;
		}
		
		foreach (self::PLUGINS as $plugn) {
			$plugn = __NAMESPACE__ . "\\inc\\plugin\\{$plugn}";
			$plugn::purge();
		}

		foreach (self::MODULES as $module) {
			$module = __NAMESPACE__ . "\\inc\\module\\{$module}";
			$plugn::purge();
		}

		return true;
	}

	/**
	 * Load geotargeting cookie before third-party cache.
	 * [Action: init].
	 *
	 * @access public
	 * @param string $cookie
	 * @return bool
	 */
	public static function setTarget(string $cookie) : bool
	{
		if ( !self::isActive() ) {

			foreach (self::PLUGINS as $plugn) {
				$plugn = __NAMESPACE__ . "\\inc\\plugin\\{$plugn}";
				if ( $plugn::isEnabled() && Helper::hasMethod($plugn, 'loadGeo') ) {
					$plugn::setTarget($cookie);
				}
				return true;
			}

		}

		return false;
	}

	/**
	 * Enable geotargeting support for third-party cache.
	 * [Action: {plugin}-activate].
	 *
	 * @access public
	 * @param string $cookie
	 * @return bool
	 */
	public static function enableTarget(string $cookie) : bool
	{
		if ( self::isActive() ) {

			foreach (self::PLUGINS as $plugn) {
				$plugn = __NAMESPACE__ . "\\inc\\plugin\\{$plugn}";
				if ( $plugn::isEnabled() && Helper::hasMethod($plugn, 'enableTarget') ) {
					$plugn::enableTarget($cookie);
				}
				return true;
			}

		}

		return false;
	}

	/**
	 * Disable geotargeting support for third-party cache.
	 * [Action: {plugin}-deactivate].
	 *
	 * @access public
	 * @param string $cookie
	 * @return bool
	 */
	public static function disableTarget(string $cookie) : bool
	{
		if ( self::isActive() ) {

			foreach (self::PLUGINS as $plugn) {
				$plugn = __NAMESPACE__ . "\\inc\\plugin\\{$plugn}";
				if ( $plugn::isEnabled() && Helper::hasMethod($plugn, 'disableTarget') ) {
					$plugn::disableTarget($cookie);
				}
				return true;
			}

		}

		return false;
	}
}
