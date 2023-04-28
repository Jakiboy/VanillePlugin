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

namespace VanillePlugin\thirdparty;

use VanillePlugin\thirdparty\inc\module\Opcache;
use VanillePlugin\thirdparty\inc\module\Apcu;
use VanillePlugin\thirdparty\inc\plugin\Redis;
use VanillePlugin\thirdparty\inc\plugin\WpRocket;
use VanillePlugin\thirdparty\inc\plugin\LiteSpeed;
use VanillePlugin\thirdparty\inc\plugin\WpOptimize;
use VanillePlugin\thirdparty\inc\plugin\W3TotalCache;
use VanillePlugin\thirdparty\inc\plugin\Kinsta;
use VanillePlugin\thirdparty\inc\plugin\WpFastestCache;
use VanillePlugin\thirdparty\inc\plugin\WpSuperCache;
use VanillePlugin\thirdparty\inc\plugin\CacheEnabler;

/**
 * Third-Party cache helper class.
 */
final class Cache
{
	/**
	 * Check whether cache is active (functional),
	 * Using WordPress cache constant.
	 * 
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function isActive()
	{
		if ( defined('WP_CACHE') && WP_CACHE == true ) {
			return true;

		} elseif ( Opcache::isEnabled() ) {
			return true;

		} elseif ( Apcu::isEnabled() ) {
			return true;
			
		} elseif ( Redis::isEnabled() ) {
			return true;
		}
		return false;
	}

	/**
	 * Purge plugin cache including server cache,
	 * Purge: Can either be flush or clear or both.
	 * 
	 * @access public
	 * @param void
	 * @return bool
	 * @internal
	 */
	public static function purge()
	{
		if ( !self::isActive() ) {
			return false;
		}

		/**
		 * Purge Opcache.
		 */
		if ( Opcache::isEnabled() ) {
			Opcache::purge();
		}
 
		/**
		 * Purge APCu.
		 */
		if ( Apcu::isEnabled() ) {
			Apcu::purge();
		}
 
		/**
		 * Purge Redis.
		 */
		if ( Redis::isEnabled() ) {
			Redis::purge();
		}

		/**
		 * Purge WP Rocket.
		 */
		if ( WpRocket::isEnabled() ) {
			WpRocket::purge();
		}
			    
		/**
		 * Purge LiteSpeed.
		 */
		if ( LiteSpeed::isEnabled() ) {
			LiteSpeed::purge();
		}

		/**
		 * Purge W3 Total Cache.
		 */
		if ( W3TotalCache::isEnabled() ) {
			W3TotalCache::purge();
		}

		/**
		 * Purge WP-Optimize.
		 */
		if ( WpOptimize::isEnabled() ) {
			WpOptimize::purge();
		}
 
		/**
		 * Purge Kinsta.
		 */
		if ( Kinsta::isEnabled() ) {
			Kinsta::purge();
		}

		/**
		 * Purge WP Fastest Cache.
		 */
		if ( WpFastestCache::isEnabled() ) {
			WpFastestCache::purge();
		}

		/**
		 * Purge WP Super Cache.
		 */
		if ( WpSuperCache::isEnabled() ) {
			WpSuperCache::purge();
		}

		/**
		 * Purge Cache Enabler.
		 */
		if ( CacheEnabler::isEnabled() ) {
			CacheEnabler::purge();
		}

		return false;
	}

	/**
	 * Load geotargeting cookie before cache.
	 * Action: init
	 * Action: load
	 * 
	 * @access public
	 * @param string $name, Cookie name
	 * @return bool
	 * @internal
	 */
	public static function loadGeotargeting($name)
	{
		if ( self::isActive() ) {

			/**
			 * WP Rocket Geotargeting.
			 */
			if ( WpRocket::isEnabled() ) {
				WpRocket::loadGeotargeting($name);
				return true;
			}

		}
		return false;
	}

	/**
	 * Enable geotargeting through cache.
	 * Action: activation
	 * 
	 * @access public
	 * @param string $name, Cookie name
	 * @return bool
	 * @internal
	 */
	public static function enableGeotargeting($name)
	{
		if ( self::isActive() ) {

			/**
			 * WP Rocket Geotargeting.
			 */
			if ( WpRocket::isEnabled() ) {
				WpRocket::enableGeotargeting($name);
				return true;
			}

		}
		return false;
	}

	/**
	 * Disable geotargeting through cache.
	 * Action: deactivation
	 * 
	 * @access public
	 * @param string $name, Cookie name
	 * @return bool
	 * @internal
	 */
	public static function disableGeotargeting($name)
	{
		if ( self::isActive() ) {

			/**
			 * WP Rocket Geotargeting.
			 */
			if ( WpRocket::isEnabled() ) {
				WpRocket::disableGeotargeting($name);
				return true;
			}

		}
		return false;
	}
}
