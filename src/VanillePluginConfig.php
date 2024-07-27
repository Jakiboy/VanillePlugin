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
    File, Json, Stringify, 
	Converter, Transient, GlobalConst
};

trait VanillePluginConfig
{
	/**
	 * @access private
	 * @var int $depth, Base path depth
	 * @var bool $cacheable, Config cache
	 * @var string $path, Config root path
	 * @var object $global, Config global
	 */
	private $depth = 5;
	private $cacheable = true;
	private $root = '/core/storage/config';
	private $global;

	/**
	 * Init config.
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
	 * Get static instance.
	 *
	 * @access protected
	 * @return object
	 */
	protected static function getStatic() : object
	{
		return new static;
	}
	
	/**
	 * Init plugin global config.
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
			$this->root = (string)constant('VanillePluginConfigPath');
		}
		if ( defined('VanillePluginCache') ) {
			$this->cacheable = (bool)constant('VanillePluginCache');
		}

		if ( $this->cacheable ) {

			$key = $this->applyPrefix('global');
			if ( !($this->global = Transient::get($key)) ) {
				$this->global = $this->parseConfig('global');
				Transient::set($key, $this->global, 0);
			}

		} else {
			$this->global = $this->parseConfig('global');
		}
	}

	/**
	 * Parse plugin config file.
	 *
	 * @access protected
	 * @param string $config
	 * @param bool $validate
	 * @return mixed
	 * @throws ConfigException
	 */
	protected function parseConfig(string $config, bool $validate = true)
	{
		$file = $this->getRoot(
			"{$this->root}/{$config}.json"
		);

		if ( !File::isFile($file) ) {
	        throw new ConfigException(
	            ConfigException::invalidConfigFile($file)
	        );
		}

		$data = Json::parse($file);
		if ( $validate ) {
			VanillePluginValidator::validate($data, $config);
		}

		return $data;
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
	protected function getConfig(?string $key = null)
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
	 * Update global config options.
	 *
	 * @access protected
	 * @param array $options
	 * @param int $args
	 * @return bool
	 */
	protected function updateConfig(array $options = [], int $args = 64|256) : bool
	{
		if ( $this->isDebug() ) {
			$args = 64|128|256;
		}

		$file = $this->getRoot(
			"{$this->root}/global.json"
		);
		$data = Json::parse($file);

		foreach ($options as $option => $value) {
			if ( isset($data->options->{$option}) ) {
				$data->options->{$option} = $value;
			}
		}

		VanillePluginValidator::validate($data, 'global');

		$data = Json::format($data, $args);
		return File::w($file, $data);
	}

	/**
	 * Load partial config file.
	 *
	 * @access protected
	 * @param string $config
	 * @param bool $validate
	 * @return array
	 */
	protected function loadConfig(string $config, bool $validate = true) : array
	{
		$data = [];

		if ( $this->cacheable ) {

			$key = $this->applyPrefix($config);
			if ( !($data = Transient::get($key)) ) {
				$data = $this->parseConfig($config, $validate);
				Transient::set($key, $data, 0);
			}

		} else {
			$data = $this->parseConfig($config, $validate);
		}

		return Converter::toArray($data);
	}

	/**
	 * Get dynamic root path.
	 *
	 * @access protected
	 * @param string $sub
	 * @return string
	 */
	protected function getRoot(?string $sub = null) : string
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
	protected function applyPrefix(string $key, bool $sep = true) : string
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
	 * @return string
	 */
	protected function getCachePath() : string
	{
		$config = $this->getConfig('path');
		$data = $config->cache ?? '/cache';
		return $this->getRoot($data);
	}

	/**
	 * Get static temp path.
	 *
	 * @access protected
	 * @param string $sub
	 * @return string
	 */
	protected function getTempPath(?string $sub = null) : string
	{
		$config = $this->getConfig('path');
		$data = $config->temp ?? '/temp';
		$path = $this->getRoot($data);
		if ( $sub ) {
			$path .= "/{$sub}";
		}
		return Stringify::formatPath($path);
	}

	/**
	 * Get static TTL.
	 *
	 * @access protected
	 * @return int
	 */
	protected function getExpireIn() : int
	{
		$config = $this->getConfig('options');
		return$config->ttl;
	}

	/**
	 * Get static timeout.
	 *
	 * @access protected
	 * @return int
	 */
	protected function getTimeout() : int
	{
		$config = $this->getConfig('options');
		return $config->timeout;
	}

	/**
	 * Get static secret key.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getSecret() : string
	{
		$config = $this->getConfig('options');
		return $config->secret;
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
	 * @param string $type
	 * @return array
	 */
	protected function getAjax(?string $type = null) : array
	{
		$this->initConfig();
		$data = $this->loadConfig('ajax');
		$this->resetConfig();
		return ($type) ? $data[$type] : $data;
	}

	/**
	 * Get Ajax admin actions.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getAdminAjax() : array
	{
		return $this->getAjax('admin');
	}

	/**
	 * Get Ajax front actions.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getFrontAjax() : array
	{
		return $this->getAjax('front');
	}

	/**
	 * Get cron data.
	 *
	 * @access protected
	 * @param string $type
	 * @return array
	 */
	protected function getCron(?string $type = null) : array
	{
		$this->initConfig();
		$data = $this->loadConfig('cron');
		$this->resetConfig();
		return ($type) ? $data[$type] : $data;
	}

	/**
	 * Get cron events.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getEvents() : array
	{
		return $this->getCron('events');
	}

	/**
	 * Get cron schedules.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getSchedules() : array
	{
		$data = [];
		foreach ($this->getCron('schedules') as $schedule) {
			$name = $schedule['name'];
			unset($schedule['name']);
			$data[$name] = $schedule;
		}
		return $data;
	}

	/**
	 * Get api routes.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getRoutes() : array
	{
		$this->initConfig();
		$data = $this->loadConfig('routes');
		$this->resetConfig();
		return $data['routes'];
	}

	/**
	 * Get requirements.
	 *
	 * @access protected
	 * @return array
	 */
	protected function getRequirements() : array
	{
		$this->initConfig();
		$data = $this->loadConfig('requirements');
		$this->resetConfig();
		return $data;
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
		if ( !($data = $this->loadConfig('assets')) ) {
			$data = $this->global->assets ?? [];
		}
		$this->resetConfig();
		return (array)$data;
	}

	/**
	 * Get strings.
	 *
	 * @access protected
	 * @param string $type
	 * @return array
	 */
	protected function getStrings(?string $type = null) : array
	{
		$this->initConfig();
		$data = $this->loadConfig('strings');
		$this->resetConfig();
		return ($type) ? $data[$type] : $data;
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
