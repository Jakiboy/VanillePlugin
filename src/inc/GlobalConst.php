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

namespace VanillePlugin\inc;

final class GlobalConst
{
	/**
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function pluginDir($path = null)
	{
		if ( $path ) {
			return Stringify::formatPath(WP_PLUGIN_DIR .'/'. $path);
		}
		return WP_PLUGIN_DIR;
	}

	/**
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function pluginMuDir($path = null)
	{
		if ( $path ) {
			return Stringify::formatPath(WPMU_PLUGIN_DIR .'/'. $path);
		}
		return WPMU_PLUGIN_DIR;
	}

	/**
	 * @access public
	 * @param string $url
	 * @return string
	 */
	public static function pluginUrl($url = null)
	{
		if ( $url ) {
			return Stringify::escapeUrl(WP_PLUGIN_URL .'/'. $url);
		}
		return WP_PLUGIN_URL;
	}
	
	/**
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function contentDir($path = null)
	{
		if ( $path ) {
			return Stringify::formatPath(WP_CONTENT_DIR .'/'. $path);
		}
		return WP_CONTENT_DIR;
	}

	/**
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function rootDir($path = null)
	{
		if ( $path ) {
			return Stringify::formatPath(ABSPATH .'/'. $path);
		}
		return ABSPATH;
	}

	/**
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function version()
	{
		global $wp_version;
		return $wp_version;
	}

	/**
	 * @access public
	 * @param void
	 * @return bool
	 */
	public static function debug()
	{
		return WP_DEBUG;
	}

	/**
	 * Check ajax.
	 *
	 * @access public
	 * @return bool
	 */
	public static function ajax() : bool
	{
		$request = Server::get('request-uri');
		return Stringify::contains($request, 'admin-ajax.php');
	}
}
