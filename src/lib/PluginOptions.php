<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.0.1
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 * Allowed to edit for plugin customization
 */

namespace VanillePlugin\lib;

use VanillePlugin\VanilleConfig;
use VanillePlugin\lib\WordPress;
use VanillePlugin\inc\Data;

class PluginOptions extends WordPress
{
	use VanilleConfig;

	/**
	 * Register a settings and its data
	 *
	 * @access protected
	 * @param inherit
	 * @return inherit
	 */
	protected function doPluginAction($action)
	{
		return $this->doAction("{$this->getNameSpace()}{$action}");
	}

	/**
	 * Register a settings and its data
	 *
	 * @access protected
	 * @param inherit
	 * @return inherit
	 */
	protected function addPluginOption($group, $name, $args = null)
	{
		$args = isset($args) ? $args : ['type' => 'string'];
		return parent::addOption("{$this->getPrefix()}{$group}","{$this->getPrefix()}{$name}",$args);
	}

	/**
	 * Retrieves an option value based on an option name
	 *
	 * @see /reference/functions/get_option/
	 * @access protected
	 * @param inherit
	 * @return inherit
	 */
	protected function getPluginOption($name, $default = null)
	{
		$default = isset($default) ? $default : false;
		return parent::getOption("{$this->getPrefix()}{$name}",$default);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @see /reference/functions/update_option/
	 * @since 4.0.0
	 * @access protected
	 * @param string $name, mixed $value
	 * @return boolean
	 */
	protected function updatePluginOption($name, $value)
	{
		return parent::updateOption("{$this->getPrefix()}{$name}",$value);
	}

	/**
	 * Retrieves an option value based on an option name as object
	 *
	 * @see /reference/functions/get_option/
	 * @since 4.0.0
	 * @access protected
	 * @param string $name
	 * @return object
	 */
	protected function getPluginObject($name)
	{
		return Data::toObject( parent::getOption("{$this->getPrefix()}{$name}") );
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @see /reference/functions/delete_option/
	 * @since 4.0.0
	 * @access protected
	 * @param string $name
	 * @return {inherit}
	 */
	protected function removePluginOption($name)
	{
		return parent::removeOption("{$this->getPrefix()}{$name}");
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @see /reference/functions/delete_option/
	 * @since 4.0.0
	 * @access protected
	 * @param string $name
	 * @return {inherit}
	 */
	protected function addPluginMenuPage($icon = 'admin-plugins')
	{
		return $this->addMenuPage(
			$this->translateString("{$this->getPluginName()} Dashboard"),
			$this->translateString($this->getPluginName()),
			"manage_{$this->getNameSpace()}",
			$this->getNameSpace(),
			[$this,'index'],
			"dashicons-{$icon}"
		);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @since 4.0.0
	 * @access protected
	 * @param {inherit}
	 * @return {inherit}
	 */
	protected function addPluginJS($path, $deps = [], $version = false, $footer = false)
	{
		$id = str_replace('.js', '', basename($path));
		$id = str_replace('.min', '', $id);
		$path = "/{$this->getNameSpace()}{$this->getAsset()}{$path}";
		$this->addJS("{$this->getNameSpace()}-{$id}",$path,$deps,$version,$footer);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @since 4.0.0
	 * @access protected
	 * @param {inherit}
	 * @return {inherit}
	 */
	protected function addPluginMainJS($path, $deps = [], $version = false, $footer = false)
	{
		$path = "/{$this->getNameSpace()}{$this->getAsset()}{$path}";
		$this->addJS("{$this->getNameSpace()}-main",$path,$deps,$version,$footer);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @since 4.0.0
	 * @access protected
	 * @param {inherit}
	 * @return {inherit}
	 */
	protected function addPluginGlobalJS($path, $deps = [], $version = false, $footer = false)
	{
		$path = "/{$this->getNameSpace()}{$this->getAsset()}{$path}";
		$this->addJS("{$this->getNameSpace()}-global",$path,$deps,$version,$footer);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @since 4.0.0
	 * @access protected
	 * @param string $name
	 * @return {inherit}
	 */
	protected function localizePluginJS($content = [], $id = 'main')
	{
		$object = "{$this->getNameSpace()}Plugin";
		$this->localizeJS("{$this->getNameSpace()}-{$id}",$object,$content);
	}

	/**
	 * Update the value of an option that was already added
	 *
	 * @since 4.0.0
	 * @access protected
	 * @param string $name
	 * @return {inherit}
	 */
	protected function addPluginCSS($path, $deps = [], $version = '', $media = 'all')
	{
		$id = str_replace('.css', '', basename($path));
		$id = str_replace('.min', '', $id);
		$path = "/{$this->getNameSpace()}{$this->getAsset()}{$path}";
		$this->addCSS("{$this->getNameSpace()}-{$id}",$path,$deps,$version,$media);
	}

	/**
	 * Add Plugin Main CSS
	 *
	 * @since 4.0.0
	 * @access protected
	 * @param string $name
	 * @return {inherit}
	 */
	protected function addPluginMainCSS($path, $deps = [], $version = '', $media = 'all')
	{
		$path = "/{$this->getNameSpace()}{$this->getAsset()}{$path}";
		echo $this->addCSS("{$this->getNameSpace()}-main",$path,$deps,$version,$media);
	}

	/**
	 * Add Plugin Global CSS
	 *
	 * @since 4.0.0
	 * @access protected
	 * @param {inherit}
	 * @return {inherit}
	 */
	protected function addPluginGlobalCSS($path, $deps = [], $version = '', $media = 'all')
	{
		$path = "/{$this->getNameSpace()}{$this->getAsset()}{$path}";
		echo $this->addCSS("{$this->getNameSpace()}-global",$path,$deps,$version,$media);
	}

	/**
	 * Check if is plugin namespace
	 *
	 * @param void
	 * @return boolean
	 */
	protected function isPluginAdmin()
	{
		$protocol = isset($_SERVER['HTTPS']) ? "https://" : "http://";
		$url = "{$protocol}{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
		if ( strpos($url,"?page={$this->getNameSpace()}") !== false ) {
			return true;
		}
		return false;
	}

	/**
	 * Get current used lang
	 *
	 * @param boolean $local
	 * @return string
	 */
	protected function getLanguage($local = false)
	{
		$lang = get_locale();
		if ($local) {
			return $lang;
		} else {
			return substr($lang, 0, strpos($lang, '_'));
		}
	}
	
	/**
	 * Get plugin root path
	 *
	 * @param null|string $path
	 * @return string
	 *
	 * @see plugin_dir_path
	 */
	// protected static function getRoot($path = null)
	// {
		// $path = isset($path) ? WP_PLUGIN_DIR . "/{$path}" : WP_PLUGIN_DIR;
		// return "{$path}";
	// }

	/**
	 * Return plugin infos
	 *
	 * @param string $name {pluginDir}/{pluginMain}.php
	 * @return void
	 */
	protected function getPluginInfo($name)
	{
		// return get_plugin_data("{$this->getRoot()}/{$name}");
	}

	/**
	 * Loads a plugin’s translated strings
	 *
	 * @param void
	 * @return void
	 *
	 * action : plugins_loaded
	 */
	public function translate()
	{
		load_plugin_textdomain($this->getNameSpace(),false,"{$this->getNameSpace()}/languages");
	}

	/**
	 * Translated string
	 *
	 * @param void
	 * @return void
	 *
	 */
	public function translateString($string)
	{
		return __($string, $this->getNameSpace());
	}

	/**
	 * Loads a plugin’s translated strings
	 *
	 * @param void
	 * @return void
	 *
	 * action : after_setup_theme
	 */
	protected static function addPluginCapability($role, $cap)
	{
		parent::addCapability($role, "{$cap}_{$this->getNameSpace()}");
	}

	/**
	 * Loads a plugin’s translated strings
	 *
	 * @param void
	 * @return void
	 *
	 * action : after_setup_theme
	 */
	protected static function removePluginCapability($role, $cap)
	{
		parent::removeCapability($role, "{$cap}_{$this->getNameSpace()}");
	}

	/**
	 * Check token
	 *
	 * @access public
	 * @param int|string $action
	 * @return boolean
	 */
	public static function checkToken($action = -1)
	{
		if ( !wp_verify_nonce( Post::get('nonce'), $action ) ) {
			die( $this->translateString('Invalid token') );
		}
	}

	/**
	 * Simple save action
	 *
	 * @access protected
	 * @param void
	 * @return boolean
	 */
	protected function saved()
	{
		if ( Get::isSetted('settings-updated') 
		&& Get::get('settings-updated') == 'true' ) {
			return true;
		}
	}

	/**
	 * @param void
	 * @return boolean
	 */
	protected static function isHttps()
	{
		if ( !empty($_SERVER['HTTPS']) 
		&& $_SERVER['HTTPS'] !== 'off' ) {
		    return true;
		}
	}
}
