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
 */

namespace VanillePlugin;

use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\File;
use VanillePlugin\inc\Json;

trait VanilleConfig
{
	/**
	 * @access private
	 * @var object $global
	 * @var string $namespace
	 */
	private $path = '/core/storage/config/global.json';
	private $global = false;
	private $namespace = false;

	/**
	 * Get static instance
	 *
	 * @access public
	 * @param void
	 * @return object
	 */
	public static function getStatic()
	{
		return new static;
	}
	
	/**
	 * Set Config Json File
	 *
	 * @param string $path
	 * @return void
	 */
	protected function initConfig($plugin)
	{
		// Define Internal Namespace
		if (!$this->namespace) {
			$this->namespace = $plugin->getNameSpace();
		}
		
		// Parse VanillePLugin Config file
		if (!$this->global) {
			$json = new Json("{$this->getRoot()}{$this->path}");
			$this->global = $json->parse();
		}
	}

	/**
	 * Get global
	 *
	 * @param void
	 * @return mixed
	 */
	public function getConfig($var = null)
	{
		if ($var) {
			return isset($this->global->$var)
			? $this->global->$var : false;
		}
		return $this->global;
	}

	/**
	 * Get static namespace
	 *
	 * @param void
	 * @return string
	 */
	public function getNameSpace()
	{
		return $this->namespace;
	}

	/**
	 * Get static namespace
	 *
	 * @param void
	 * @return string
	 */
	public function getPluginName()
	{
		return $this->global->name;
	}

	/**
	 * Get static namespace
	 *
	 * @param void
	 * @return string
	 */
	public function getPluginDescription()
	{
		return $this->global->description;
	}

	/**
	 * Get static namespace
	 *
	 * @param void
	 * @return string
	 */
	public function getPluginAuthor()
	{
		return $this->global->author;
	}

	/**
	 * Get static namespace
	 *
	 * @param void
	 * @return string
	 */
	public function getPluginVersion()
	{
		return $this->global->version;
	}

	/**
	 * Get static prefix
	 *
	 * @param void
	 * @return string
	 */
	public function getPrefix()
	{
		return "{$this->getNameSpace()}_";
	}

	/**
	 * Get static prefix
	 *
	 * @param void
	 * @return string
	 */
	public function getAssetUri()
	{
		return "{$this->getBaseUri()}{$this->global->path->asset}";
	}
	
	/**
	 * Get static prefix
	 *
	 * @param void
	 * @return string
	 */
	public function getAsset()
	{
		return "/{$this->getNameSpace()}{$this->global->path->asset}";
	}

	/**
	 * Get static migrate
	 *
	 * @param void
	 * @return string
	 */
	public function getMigrate()
	{
		return "{$this->getRoot()}{$this->global->path->migrate}";
	}

	/**
	 * Get static migrate
	 *
	 * @param void
	 * @return string
	 */
	public function getCachePath()
	{
		return "{$this->getRoot()}{$this->global->path->cache}";
	}

	/**
	 * Get static migrate
	 *
	 * @param void
	 * @return string
	 */
	public function getViewPath()
	{
		return "{$this->getRoot()}{$this->global->path->view}";
	}

	/**
	 * Get logs path
	 *
	 * @param void
	 * @return string
	 */
	public function getLoggerPath()
	{
		return "{$this->getRoot()}{$this->global->path->logs}";
	}

	/**
	 * Get static migrate
	 *
	 * @param void
	 * @return string
	 */
	public function getViewExtension()
	{
		return $this->global->view->extension;
	}

	/**
	 * Get static root
	 *
	 * @param void
	 * @return string
	 */
	public function getRoot()
	{
		return wp_normalize_path( WP_PLUGIN_DIR . '/' . self::getNameSpace() );
	}

	/**
	 * Get static root
	 *
	 * @param void
	 * @return string
	 */
	public function getMainFile()
	{
		return self::getNameSpace() . '/' . self::getNameSpace() . '.php';
	}

	/**
	 * Get static Base Uri
	 *
	 * @param void
	 * @return string
	 */
	public function getBaseUri()
	{
		return WP_PLUGIN_URL . '/' . self::getNameSpace();
	}

	/**
	 * Get ajax Uri
	 *
	 * @param void
	 * @return string
	 */
	public function getAjaxUrl()
	{
		return admin_url('admin-ajax.php');
	}

	/**
	 * Get ajax Uri
	 *
	 * @param void
	 * @return string
	 */
	public function getAjax()
	{
		return $this->global->ajax;
	}

	/**
	 * Get Requirements
	 *
	 * @param void
	 * @return string
	 */
	public function getRequirement()
	{
		return $this->global->requirement;
	}

	/**
	 * Get Debug
	 *
	 * @param void
	 * @return boolean
	 */
	public function isDebug()
	{
		return $this->global->options->debug;
	}

	/**
	 * Update Custom Options
	 *
	 * @param array $options
	 * @return void
	 */
	public function updateConfig($options = [])
	{
		$json = new Json("{$this->getRoot()}{$this->path}");
		$config = $json->parse(true);
		foreach ($options as $option => $value) {
			if ( isset($config['options'][$option]) ) {
				$config['options'][$option] = $value;
			}
		}
		$config = Json::format($config);
		File::write("{$this->getRoot()}{$this->path}",$config);
	}
}
