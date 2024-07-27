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

namespace VanillePlugin;

use VanillePlugin\exc\ConfigException;
use VanillePlugin\inc\{
	File, Json, Stringify, Transient, GlobalConst
};

trait VanillePluginConfig
{
	/**
	 * @access private
	 * @var int $depth, Base path depth
	 * @var bool $cacheable, Config cache
	 * @var string $config, Config path
	 * @var object $global, Config global
	 */
	private $depth = 5;
	private $cacheable = true;
	private $config = '/core/storage/config/global.json';
	private $global;

	/**
	 * Init configuration.
	 */
	public function __construct()
	{
		$this->initConfig();
	}

	/**
	 * Prevent object clone.
	 */
    public function __clone()
    {
        die(__METHOD__ . ': Clone denied');
    }

	/**
	 * Prevent object serialization.
	 */
    public function __wakeup()
    {
        die(__METHOD__ . ': Unserialize denied');
    }

	/**
	 * Get static instance,
	 * Allows access to plugin config.
	 *
	 * @access protected
	 * @return object
	 */
	protected static function getStatic()
	{
		return new static;
	}

	/**
	 * Init plugin config.
	 *
	 * @access protected
	 * @return void
	 */
	protected function initConfig()
	{
		if ( isset($this->global) ) {
			return;
		}

		// Override config
		if ( defined('VanillePluginDepth') ) {
			$this->depth = (int)constant('VanillePluginDepth');
		}
		if ( defined('VanillePluginConfigPath') ) {
			$this->config = (string)constant('VanillePluginConfigPath');
		}
		if ( defined('VanillePluginCache') ) {
			$this->cacheable = (bool)constant('VanillePluginCache');
		}

		if ( $this->cacheable ) {

			$key = $this->applyPrefix('global');
			if ( !($this->global = Transient::get($key)) ) {
				$this->global = $this->parseConfig();
				Transient::set($key, $this->global, 0);
			}

		} else {
			$this->global = $this->parseConfig();
		}
	}
	
	/**
	 * Parse plugin configuration file.
	 *
	 * @access protected
	 * @return mixed
	 * @throws ConfigException
	 */
	protected function parseConfig()
	{
		$json = $this->getRoot($this->config);
		if ( File::exists($json) ) {

			$global = Json::parse($json);
			VanillePluginValidator::checkConfig($global, $json);
			return $global;

		} else {
	        throw new ConfigException(
	            ConfigException::invalidConfigFile($json)
	        );
		}
	}

	/**
	 * Reset config object.
	 *
	 * @access protected
	 * @return void
	 */
	protected function resetConfig()
	{
		unset($this->global);
	}

	/**
	 * Get global config option.
	 *
	 * @access protected
	 * @param string $key
	 * @return mixed
	 */
	protected function getConfig($key = null)
	{
		$this->initConfig();
		if ( $key ) {
			$data = $this->global->{$key} ?? null;
			
		} else {
			$data = $this->global;
		}
		$this->resetConfig();
		return $data;
	}

	/**
	 * Update global custom options.
	 *
	 * @access protected
	 * @param array $options
	 * @param int $args
	 * @return void
	 */
	protected function updateConfig($options = [], $args = 64|256)
	{
		$json = $this->getRoot($this->config);
		$data = Json::parse($json, true);

		foreach ($options as $option => $value) {
			if ( isset($data['options'][$option]) ) {
				$data['options'][$option] = $value;
			}
		}

		$data['routes'] = (object)$data['routes'];
		$data['assets'] = (object)$data['assets'];

		if ( $this->isDebug() ) {
			$args = 64|128|256;
		}

		$data = Json::format($data, $args);
		File::w($this->getRoot($this->config), $data);
	}

	/**
	 * Load configuration file.
	 *
	 * @access protected
	 * @param string $config
	 * @param bool $isArray
	 * @return mixed
	 */
	protected function loadConfig($config, $isArray = false)
	{
		$value = false;
		$dir = dirname($this->getRoot($this->config));

		if ( $this->cacheable ) {
			$key = $this->applyPrefix($config);
			if ( !($value = Transient::get($key)) ) {
				if ( File::exists( ($json = "{$dir}/{$config}.json") ) ) {
					$value = Json::decode(File::r($json), $isArray);
				}
				Transient::set($key, $value, 0);
			}

		} else {
			if ( File::exists( ($json = "{$dir}/{$config}.json") ) ) {
				$value = Json::decode(File::r($json), $isArray);
			}
		}

		return $value;
	}

	/**
	 * Set global config path.
	 *
	 * @access protected
	 * @param string $path
	 * @return void
	 */
	protected function setConfigPath($path = '/global.json')
	{
		$this->config = $path;
	}

	/**
	 * Get dynamic namespace by root.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getNameSpace()
	{
		return Stringify::slugify(
			Stringify::basename($this->getRoot())
		);
	}

	/**
	 * Apply plugin namespace.
	 *
	 * @access protected
	 * @param string $key
	 * @param string $sep
	 * @return string
	 */
	protected function applyNamespace(string $key, string $sep = '-') : string
	{
		return "{$this->getNameSpace()}{$sep}{$key}";
	}

	/**
	 * Get static namespace.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getPluginName()
	{
		return $this->getConfig('name');
	}

	/**
	 * Get static description.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getPluginDescription()
	{
		return $this->getConfig('description');
	}

	/**
	 * Get static namespace.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getPluginAuthor()
	{
		return $this->getConfig('author');
	}

	/**
	 * Get static version.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getPluginVersion()
	{
		return $this->getConfig('version');
	}

	/**
	 * Get static prefix.
	 *
	 * @access protected
	 * @param bool $sep
	 * @return string
	 */
	protected function getPrefix($sep = true)
	{
		$prefix = Stringify::undash(
			$this->getNameSpace()
		);
		if ( $sep ) {
			$prefix = "{$prefix}_";
		}
		return $prefix;
	}

	/**
	 * Apply plugin prefix.
	 *
	 * @access protected
	 * @param string $key
	 * @param bool $sep, Separator
	 * @return string
	 */
	protected function applyPrefix($key, $sep = true)
	{
		$key = Stringify::undash($key);
		return "{$this->getPrefix($sep)}{$key}";
	}

	/**
	 * Get static assets url.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getAssetUrl()
	{
		$config = $this->getConfig('path');
		$data = $config->asset ?? '/assets';
		return "{$this->getBaseUrl()}{$data}";
	}
	
	/**
	 * Get static assets relative path.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getAsset()
	{
		$config = $this->getConfig('path');
		$data = $config->asset ?? '/assets';
		return "/{$this->getNameSpace()}{$data}";
	}

	/**
	 * Get static migrate path.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getMigrate()
	{
		$config = $this->getConfig('path');
		$data = $config->migrate ?? '/migrate';
		return $this->getRoot($data);
	}

	/**
	 * Get static cache path.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getCachePath()
	{
		$config = $this->getConfig('path');
		$data = $config->cache ?? '/cache';
		return $this->getRoot($data);
	}

	/**
	 * Get static temp path.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getTempPath()
	{
		$config = $this->getConfig('path');
		$data = $config->temp ?? '/temp';
		return $this->getRoot($data);
	}

	/**
	 * Get static expire.
	 *
	 * @access protected
	 * @param void
	 * @return int
	 */
	protected function getExpireIn()
	{
		$config = $this->getConfig('options');
		$data = $config->ttl ?? 0;
		return (int)$data;
	}
	
	/**
	 * Get static view path.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getViewPath()
	{
		$config = $this->getConfig('path');
		$data = $config->view ?? '/view';
		return $this->getRoot($data);
	}

	/**
	 * Get static logs path.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getLoggerPath()
	{
		$config = $this->getConfig('path');
		$data = $config->logs ?? '/logs';
		return $this->getRoot($data);
	}

	/**
	 * Get static view extension.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getViewExtension()
	{
		$config = $this->getConfig('options');
		$data = $config->view->extension ?? '.html';
		return (string)$data;
	}

	/**
	 * Get dynamic relative root.
	 *
	 * @access protected
	 * @param string $sub
	 * @return string
	 */
	protected function getRoot($sub = null)
	{
		$path = __DIR__;
		for ($i=0; $i < $this->depth; $i++) {
			$path = dirname($path);
		}
		if ( $sub ) {
			$path .= "/{$sub}";
		}
		return Stringify::formatPath($path);
	}

	/**
	 * Get main filename.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getMainFile()
	{
		$namespace = $this->getNameSpace();
		return "{$namespace}/{$namespace}.php";
	}

	/**
	 * Get main file path.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getMainFilePath()
	{
		return $this->getRoot("{$this->getNameSpace()}.php");
	}

	/**
	 * Get static Base url.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getBaseUrl()
	{
		return GlobalConst::pluginUrl("{$this->getNameSpace()}");
	}

	/**
	 * Get ajax url.
	 *
	 * @access protected
	 * @param string $scheme
	 * @return string
	 */
	protected function getAjaxUrl($scheme = 'admin')
	{
		return $this->getAdminUrl('admin-ajax.php', $scheme);
	}

	/**
	 * Get admin url.
	 *
	 * @access protected
	 * @param string $url
	 * @param string $scheme
	 * @return string
	 */
	protected function getAdminUrl($url = null, $scheme = 'admin')
	{
		return admin_url($url,$scheme);
	}

	/**
	 * Get ajax actions.
	 *
	 * @access protected
	 * @param void
	 * @return object
	 */
	protected function getAjax()
	{
		$this->initConfig();
		if ( !($data = $this->loadConfig('ajax')) ) {
			$data = $this->global->ajax ?? [];
		}
		$this->resetConfig();
		return (object)$data;
	}

	/**
	 * Get Ajax admin actions.
	 *
	 * @access public
	 * @param void
	 * @return array
	 */
	public function getAdminAjax()
	{
		$data = $this->getAjax()->admin ?? [];
		return (array)$data;
	}

	/**
	 * Get Ajax front actions.
	 *
	 * @access public
	 * @param void
	 * @return array
	 */
	public function getFrontAjax()
	{
		$data = $this->getAjax()->front ?? [];
		return (array)$data;
	}

	/**
	 * Get api routes.
	 *
	 * @access protected
	 * @param void
	 * @return object
	 */
	protected function getRoutes()
	{
		$this->initConfig();
		if ( !($data = $this->loadConfig('routes', true)) ) {
			$data = $this->global->routes ?? [];
		}
		$this->resetConfig();
		return (array)$data;
	}

	/**
	 * Get requirements.
	 *
	 * @access protected
	 * @param void
	 * @return object
	 */
	protected function getRequirement()
	{
		$this->initConfig();
		if ( !($data = $this->loadConfig('requirements')) ) {
			$data = $this->global->requirements ?? [];
		}
		$this->resetConfig();
		return (object)$data;
	}
	
	/**
	 * Get remote assets.
	 *
	 * @access protected
	 * @param void
	 * @return array
	 */
	protected function getRemoteAsset()
	{
		$this->initConfig();
		if ( !($data = $this->loadConfig('assets', true)) ) {
			$data = $this->global->assets ?? [];
		}
		$this->resetConfig();
		return (array)$data;
	}

	/**
	 * Get strings.
	 *
	 * @access protected
	 * @param void
	 * @return array
	 */
	protected function getStrings()
	{
		$this->initConfig();
		if ( !($data = $this->loadConfig('strings', true)) ) {
			$data = $this->global->strings ?? [];
		}
		$this->resetConfig();
		return (array)$data;
	}

	/**
	 * Get multilingual status.
	 *
	 * @access protected
	 * @param void
	 * @return bool
	 */
	protected function isMultilingual()
	{
		$config = $this->getConfig('options');
		$data = $config->multilingual ?? false;
		return (bool)$data;
	}

	/**
	 * Get multisite status.
	 *
	 * @access protected
	 * @param void
	 * @return bool
	 */
	protected function allowedMultisite()
	{
		$config = $this->getConfig('options');
		$data = $config->multisite ?? false;
		return (bool)$data;
	}

	/**
	 * Get debug status.
	 *
	 * @access public
	 * @param bool $global
	 * @return bool
	 */
	public function isDebug($global = true)
	{
		if ( $global ) {
			return GlobalConst::debug();
		}
		$config = $this->getConfig('options');
		$debug = $config->debug ?? false;
		return (bool)$debug;
	}
}
