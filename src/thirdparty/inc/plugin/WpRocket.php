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
 * WP Rocket plugin helper class.
 *
 * @see https://github.com/wp-media/
 */
final class WpRocket
{
	/**
	 * @access public
	 */
	public static $options = [];

	/**
	 * Check whether plugin is enabled.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isEnabled() : bool
	{
		return Helper::isFunction('rocket_clean_domain');
	}

	/**
	 * Purge cache.
	 *
	 * @access public
	 * @return bool
	 */
	public static function purge() : bool
	{
		if ( self::isEnabled() ) {
			rocket_clean_domain();
			return true;
		}
		return false;
	}

	/**
	 * Force geotargeting rules through cache using cookies.
	 *
	 * @access public
	 * @param string $cookie
	 * @return void
	 */
	public static function setTarget(string $cookie)
	{
		// Set options
		static::$options['geotargeting'] = "--{$cookie}";

		// Creates cache file for each value of a specified cookie
		Helper::addFilter('rocket_cache_dynamic_cookies', [static::class, 'setCookies'], 80);

		// Prevents caching until the specified cookie is set
		Helper::addFilter('rocket_cache_mandatory_cookies', [static::class, 'setCookies'], 80);
	}

	/**
	 * Enabling geotargeting through cache using cookies.
	 *
	 * @access public
	 * @param string $cookie
	 * @return void
	 */
	public static function enableTarget(string $cookie)
	{
		// Set options
		static::$options['geotargeting'] = "--{$cookie}";

		// Creates cache file for each value of a specified cookie
		Helper::addFilter('rocket_cache_dynamic_cookies', [static::class, 'setCookies'], 80);

		// Prevents caching until the specified cookie is set
		Helper::addFilter('rocket_cache_mandatory_cookies', [static::class, 'setCookies'], 80);

		// Update rewrite
		self::updateRewrite();

		// Regenerate configuration file
		self::regenerateConfiguration();

		// Clear cache
		self::purge();
	}

	/**
	 * Disabling cache geotargeting.
	 *
	 * @access public
	 * @param string $cookie
	 * @return void
	 */
	public static function disableTarget(string $cookie)
	{
		// Set options
		static::$options['geotargeting'] = "--{$cookie}";
		
		// Remove dynamic cookies
		Helper::removeFilter('rocket_cache_dynamic_cookies', [static::class, 'setCookies'], 80);

		// Remove mandatory cookies
		Helper::removeFilter('rocket_cache_mandatory_cookies', [static::class, 'setCookies'], 80);

		// Update rewrite
		self::updateRewrite();

		// Regenerate configuration file
		self::regenerateConfiguration();

		// Clear cache
		self::purge();
	}

	/**
	 * Cache dynamic cookies.
	 * [Filter: rocket_cache_dynamic_cookies].
	 * [Filter: rocket_cache_mandatory_cookies].
	 *
	 * @access public
	 * @param array $cookies
	 * @return array
	 */
	public static function setCookies(array $cookies) : array
	{
		$cookies[] = static::$options['geotargeting'] ?? '--default-country';
		return $cookies;
	}

	/**
	 * Update rewrite (htaccess).
	 *
	 * @access public
	 * @return void
	 */
	public static function updateRewrite()
	{
		if ( Helper::isFunction('flush_rocket_htaccess') ) {
			flush_rocket_htaccess();
		}
	}

	/**
	 * Regenerate configuration (file).
	 *
	 * @access public
	 * @return void
	 */
	public static function regenerateConfiguration()
	{
		if ( Helper::isFunction('rocket_generate_config_file')
		  && Helper::isFunction('get_rocket_cache_reject_uri') ) {
			rocket_generate_config_file();
		}
	}
}
