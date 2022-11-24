<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.2
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\thirdparty\inc\plugin;

/**
 * WP Rocket plugin helper class.
 * 
 * @see https://github.com/wp-media/
 */
final class WpRocket
{
	public static $options = [];

	/**
	 * Check whether plugin is enabled.
	 * 
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function isEnabled()
	{
		return defined('WP_ROCKET_VERSION');
	}

	/**
	 * Purge cache.
	 * 
	 * @access public
	 * @param void
	 * @return bool
	 * @internal
	 */
	public static function purge()
	{
		if ( function_exists('rocket_clean_domain') ) {
			rocket_clean_domain();
			return true;
		}
		return false;
	}

	/**
	 * Force enabling geotargeting through cache using cookies.
	 * 
	 * @access public
	 * @param string $name
	 * @return void
	 */
	public static function enableGeotargeting($name)
	{
		// Set options
		static::$options['geotargeting'] = $name;

		// Disable plugin rewrite
		self::disableRewrite();

		// Creates cache file for each value of a specified cookie
		add_filter('rocket_cache_dynamic_cookies',[new self,'setCookies'],80);

		// Prevents caching until the specified cookie is set
		add_filter('rocket_cache_mandatory_cookies',[new self,'setCookies'],80);

		// Reset rewrite & configuration
		self::reset();
	}

	/**
	 * Disabling cache geotargeting.
	 * 
	 * @access public
	 * @param string $name
	 * @return void
	 */
	public static function disableGeotargeting($name)
	{
		// Set options
		static::$options['geotargeting'] = $name;

		// Enable plugin rewrite
		self::enableRewrite();
		
		// Remove dynamic cookies
		remove_filter('rocket_cache_dynamic_cookies',[new self,'setCookies'],80);

		// Remove mandatory cookies
		remove_filter('rocket_cache_mandatory_cookies',[new self,'setCookies'],80);

		// Reset rewrite & configuration
		self::reset();
	}

	/**
	 * Cache dynamic cookies.
	 * Filter: rocket_cache_dynamic_cookies
	 * Filter: rocket_cache_mandatory_cookies
	 * 
	 * @access public
	 * @param array $cookies
	 * @return array
	 */
	public static function setCookies($cookies)
	{
		$name = static::$options['geotargeting'] ?? '--default-country';
		$cookies[] = $name;
		return $cookies;
	}

	/**
	 * Reset rewrite & configuration.
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function reset()
	{
		// Update rewrite
		self::updateRewrite();

		// Regenerate configuration file
		self::regenerateConfiguration();
	}

	/**
	 * Update rewrite (htaccess).
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function updateRewrite()
	{
		if ( function_exists('flush_rocket_htaccess') ) {
			flush_rocket_htaccess();
		}
	}

	/**
	 * Regenerate configuration (file).
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function regenerateConfiguration()
	{
		if ( function_exists('rocket_generate_config_file')
		  && function_exists('get_rocket_cache_reject_uri') ) {
			rocket_generate_config_file();
		}
	}

	/**
	 * Disable plugin rewrite.
	 * Filter: rocket_htaccess_mod_rewrite
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function disableRewrite()
	{
		add_filter('rocket_htaccess_mod_rewrite','__return_false',80);
	}

	/**
	 * Enable plugin rewrite.
	 * Filter: rocket_htaccess_mod_rewrite
	 * 
	 * @access public
	 * @param void
	 * @return void
	 */
	public static function enableRewrite()
	{
		remove_filter('rocket_htaccess_mod_rewrite','__return_false',80);
	}
}
