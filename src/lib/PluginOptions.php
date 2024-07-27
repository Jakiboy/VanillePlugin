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

namespace VanillePlugin\lib;

use VanillePlugin\VanillePluginConfig;
use VanillePlugin\inc\{
	Stringify, TypeCheck, Arrayify,
	HttpRequest, File, Server, Localization,
	Response, GlobalConst
};
use VanillePlugin\thirdparty\Translator;

/**
 * Wrapper class for advanced Plugin Options API,
 * Defines only base functions used by plugins.
 * 
 * @see https://developer.wordpress.org/plugins/
 */
class PluginOptions extends WordPress
{
	use VanillePluginConfig;

	/**
	 * Add plugin action.
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $callback
	 * @param int $priority
	 * @param int $args
	 * @return true
	 */
	protected function addPluginAction($hook, $callback, $priority = 10, $args = 1)
	{
		$hook = $this->applyNamespace($hook);
		return $this->addAction($hook, $callback, $priority, $args);
	}

	/**
	 * Remove plugin action.
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $callback
	 * @param int $priority
	 * @return bool
	 */
	protected function removePluginAction($hook, $callback, $priority = 10)
	{
		$hook = $this->applyNamespace($hook);
		return $this->removeAction($hook, $hook, $callback, $priority);
	}

	/**
	 * Call plugin action.
	 *
	 * @access protected
	 * @param string $hook
	 * @param array $args
	 * @return void
	 */
	protected function doPluginAction($hook, ...$args)
	{
		$hook = $this->applyNamespace($hook);
		$this->doAction($hook, ...$args);
	}

	/**
	 * Check whether plugin has action.
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $callback
	 * @return mixed
	 */
	protected function hasPluginAction($hook, $callback = false)
	{
		$hook = $this->applyNamespace($hook);
		return $this->hasAction($hook, $callback);
	}

	/**
	 * Add plugin filter.
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $callback
	 * @param int $priority
	 * @param int $args
	 * @return true
	 */
	protected function addPluginFilter($hook, $callback, $priority = 10, $args = 1)
	{
		$hook = $this->applyNamespace($hook);
		return $this->addFilter($hook, $callback, $priority, $args);
	}

	/**
	 * Remove plugin filter.
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $callback
	 * @param int $priority
	 * @return bool
	 */
	protected function removePluginFilter($hook, $callback, $priority = 10)
	{
		$hook = $this->applyNamespace($hook);
		return $this->removeFilter($hook, $callback, $priority);
	}

	/**
	 * Apply plugin filter.
	 *
	 * @access protected
	 * @param string $hook
	 * @param mixed $value
	 * @param mixed $args
	 * @return mixed
	 */
	protected function applyPluginFilter($hook, $value, ...$args)
	{
		$hook = $this->applyNamespace($hook);
		return $this->applyFilter($hook, $value, ...$args);
	}

	/**
	 * Check whether plugin has filter.
	 *
	 * @access protected
	 * @param string $hook
	 * @param mixed $callback
	 * @return bool
	 */
	protected function hasPluginFilter($hook, $callback = false)
	{
		$hook = $this->applyNamespace($hook);
		return $this->hasFilter($hook, $callback);
	}

	/**
	 * Add plugin shortcode.
	 *
	 * @access protected
	 * @param callable $callback
	 * @param string $tag
	 * @return void
	 */
	protected function addPluginShortcode($callback, $tag = '')
	{
		if ( empty($tag) ) {
			$tag = $this->getNameSpace();
		} else {
			$tag = Stringify::slugify($tag);
			$tag = "{$this->getNameSpace()}-{$tag}";
		}
		$this->addShortcode($tag, $callback);
	}

	/**
	 * Remove plugin shortcode.
	 *
	 * @access protected
	 * @param string $tag
	 * @return void
	 */
	protected function removePluginShortcode($tag = '')
	{
		if ( empty($tag) ) {
			$tag = $this->getNameSpace();
		} else {
			$tag = Stringify::slugify($tag);
			$tag = "{$this->getNameSpace()}-{$tag}";
		}
		$this->removeShortcode($tag);
	}

	/**
	 * Checks Whether plugin shortcode exists.
	 *
	 * @access protected
	 * @param string $tag
	 * @return bool
	 */
	protected function pluginShortcodeExists($tag = '')
	{
		if ( empty($tag) ) {
			$tag = $this->getNameSpace();
		} else {
			$tag = Stringify::slugify($tag);
			$tag = "{$this->getNameSpace()}-{$tag}";
		}
		return $this->shortcodeExists($tag);
	}

	/**
	 * Register plugin settings.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function registerPluginOption(string $group, string $key, array $args = [], bool $lang = false)
	{
		$group = $this->applyPrefix($group);
		$key = $this->getOptionLang($key, $lang);
		$this->registerOption($group, $key, $args);
	}

	/**
	 * Add plugin option.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function addPluginOption(string $key, $value, bool $lang = false)
	{
		$key = $this->getOptionLang($key, $lang);
		return $this->addOption($key, $value);
	}

	/**
	 * Get plugin option.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getPluginOption(string $key, $default = [], bool $lang = false)
	{
		$key = $this->getOptionLang($key, $lang);
		$value = Stringify::deepStripSlash(
			$this->getOption($key, $default)
		);
		return $value;
	}

	/**
	 * Update plugin option.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function updatePluginOption(string $key, $value, bool $lang = false)
	{
		$key = $this->getOptionLang($key, $lang);
		return $this->updateOption($key, $value);
	}

	/**
	 * Remove plugin option.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function removePluginOption(string $key, bool $lang = false)
	{
		$key = $this->getOptionLang($key, $lang);
		return $this->removeOption($key);
	}

	/**
	 * Remove all plugin options (Under namespace).
	 *
	 * @access protected
	 * @param void
	 * @return bool
	 */
	protected function removePluginOptions()
	{
		if ( empty($prefix = $this->getNameSpace()) ) {
			return false;
		}
		$db = new Orm();
		$query = "DELETE FROM {$db->prefix}options WHERE `option_name` LIKE '%{$prefix}_%';";
		return (bool)$db->query($query);
	}

	/**
	 * Add plugin menu page.
	 *
	 * @access protected
	 * @param array $settings
	 * @return string
	 */
	protected function addPluginMenuPage($settings = [])
	{
		// Set title
		$title = $settings['title'] ?? '';
		if ( empty($title) ) {
			$title = "{$this->getPluginName()} Dashboard";
		}
		$title = $this->translate($title);

		// Set menu
		$menu = $settings['menu'] ?? '';
		if ( empty($menu) ) {
			$menu = $this->getPluginName();
		}
		$menu = $this->translate($menu);

		// Set capability
		$cap = $settings['capability'] ?? '';
		if ( empty($cap) ) {
			$prefix = Stringify::replace('-', '_', $this->getNameSpace());
			$cap = "manage_{$prefix}";
		}

		// Set slug
		$slug = $settings['slug'] ?? '';
		if ( empty($slug) ) {
			$slug = $this->getNameSpace();
		}

		// Set callback 
		$callback = $settings['callback'] ?? '';
		if ( empty($callback) ) {
			if ( TypeCheck::hasMethod($this,'index') ) {
				$callback = [$this,'index'];
			}
		}

		// Set icon
		$icon = $settings['icon'] ?? 'admin-plugins';
		if ( !empty($icon) && !Stringify::contains($icon,'http') ) {
			$icon = "dashicons-{$icon}";
		}

		return $this->addMenuPage($title,$menu,$cap,$slug,$callback,$icon);
	}

	/**
	 * Add plugin submenu page.
	 *
	 * @access protected
	 * @param array $settings
	 * @return mixed
	 */
	protected function addPluginSubMenuPage($settings = [])
	{
		// Set parent
		$parent = $settings['parent'] ?? '';
		if ( empty($parent) ) {
			$parent = $this->getNameSpace();
		}

		// Set title
		$title = $settings['title'] ?? '';
		if ( empty($title) ) {
			$title = "{$this->getPluginName()} Dashboard";
		}
		$title = $this->translate($title);

		// Set menu
		$menu = $settings['menu'] ?? '';
		if ( empty($menu) ) {
			$menu = $this->getPluginName();
		}
		$menu = $this->translate($menu);

		// Set icon
		$icon = $settings['icon'] ?? '';
		if ( !empty($icon) ) {
			$menu = "{$icon} {$menu}";
		}

		// Set capability
		$cap = $settings['capability'] ?? '';
		if ( empty($cap) ) {
			$prefix = Stringify::replace('-','_',$this->getNameSpace());
			$cap = "manage_{$prefix}";
		}

		// Set slug
		$slug = $settings['slug'] ?? '';
		if ( empty($slug) ) {
			$slug = $this->getNameSpace();
		} else {
			$slug = "{$this->getNameSpace()}-{$slug}";
		}

		// Set callback
		$callback = $settings['callback'] ?? '';
		if ( empty($callback) ) {
			if ( TypeCheck::hasMethod($this,'index') ) {
				$callback = [$this,'index'];
			}
		}

		return $this->addSubMenuPage($parent,$title,$menu,$cap,$slug,$callback);
	}

	/**
	 * Reset plugin submenu.
	 *
	 * @access protected
	 * @param string $title
	 * @param string $icon
	 * @return void
	 */
	protected function resetPluginSubMenu($title = null, $icon = null)
	{
		global $submenu;
		if ( isset($submenu[$this->getNameSpace()]) ) {
			if ( $title ) {
				$title = $this->translate($title);
				if ( $icon ) {
					$title = "{$icon} {$title}";
				}
				$submenu[$this->getNameSpace()][0][0] = $title;
			} else {
				unset($submenu[$this->getNameSpace()][0]);
			}
		}
	}

	/**
	 * Update the value of an option that was already added.
	 *
	 * @access protected
	 * @param string $path
	 * @param mixed $deps
	 * @param mixed $version false
	 * @param string $footer true
	 * @return void
	 */
	protected function addPluginJS($path, $deps = [], $version = false, $footer = true)
	{
		$id = Stringify::replace('.js','',basename($path));
		$id = Stringify::replace('.min','',$id);
		$path = "{$this->getAsset()}{$path}";
		$this->addJS("{$this->getNameSpace()}-{$id}",$path,$deps,$version,$footer);
	}

	/**
	 * Update the value of an option that was already added.
	 *
	 * @access protected
	 * @param string $path
	 * @param mixed $deps
	 * @param mixed $version false
	 * @param string $footer true
	 * @return void
	 */
	protected function addPluginMainJS($path, $deps = [], $version = false, $footer = true)
	{
		$path = "{$this->getAsset()}{$path}";
		$this->addJS("{$this->getNameSpace()}-main",$path,$deps,$version,$footer);
	}

	/**
	 * Update the value of an option that was already added.
	 *
	 * @access protected
	 * @param string $path
	 * @param mixed $deps
	 * @param mixed $version false
	 * @param string $footer true
	 * @return void
	 */
	protected function addPluginGlobalJS($path, $deps = [], $version = false, $footer = true)
	{
		$path = "{$this->getAsset()}{$path}";
		$this->addJS("{$this->getNameSpace()}-global",$path,$deps,$version,$footer);
	}

	/**
	 * Update the value of an option that was already added.
	 *
	 * @access protected
	 * @param array $name
	 * @param string $id
	 * @return void
	 */
	protected function localizePluginJS($content = [], $id = 'main')
	{
		$prefix = Stringify::replace('-','',$this->getNameSpace());
		$object = "{$prefix}Plugin";
		$this->localizeJS("{$this->getNameSpace()}-{$id}",$object,$content);
	}

	/**
	 * Update the value of an option that was already added.
	 *
	 * @access protected
	 * @param array $name
	 * @param string $id
	 * @return void
	 */
	protected function localizeGlobalJS($content = [], $id = 'global')
	{
		$prefix = Stringify::replace('-','',$this->getNameSpace());
		$object = "{$prefix}Global";
		$this->localizeJS("{$this->getNameSpace()}-{$id}",$object,$content);
	}

	/**
	 * Update the value of an option that was already added.
	 *
	 * @access protected
	 * @param string $path
	 * @param mixed $deps
	 * @param mixed $version false
	 * @param string $media
	 * @return void
	 */
	protected function addPluginCSS($path, $deps = [], $version = false, $media = 'all')
	{
		$id = Stringify::replace('.css','',basename($path));
		$id = Stringify::replace('.min','',$id);
		$path = "{$this->getAsset()}{$path}";
		$this->addCSS("{$this->getNameSpace()}-{$id}",$path,$deps,$version,$media);
	}

	/**
	 * Add Plugin Main CSS.
	 *
	 * @access protected
	 * @param string $path
	 * @param mixed $deps
	 * @param mixed $version false
	 * @param string $media
	 * @return void
	 */
	protected function addPluginMainCSS($path, $deps = [], $version = false, $media = 'all')
	{
		$path = "{$this->getAsset()}{$path}";
		$this->addCSS("{$this->getNameSpace()}-main",$path,$deps,$version,$media);
	}

	/**
	 * Add Plugin Global CSS.
	 *
	 * @access protected
	 * @param string $path
	 * @param mixed $deps
	 * @param mixed $version false
	 * @param string $media
	 * @return void
	 */
	protected function addPluginGlobalCSS($path, $deps = [], $version = false, $media = 'all')
	{
		$path = "{$this->getAsset()}{$path}";
		$this->addCSS("{$this->getNameSpace()}-global",$path,$deps,$version,$media);
	}

	/**
	 * Retrieves the value of a transient.
	 *
	 * @see /reference/functions/get_transient/
	 * @access protected
	 * @param string $key
	 * @return mixed
	 */
	protected function getTransient($key)
	{
		return get_transient($this->formatTransientKey($key));
	}

	/**
	 * Retrieves the value of a site transient.
	 *
	 * @see /reference/functions/get_site_transient/
	 * @access protected
	 * @param string $key
	 * @return mixed
	 */
	protected function getSiteTransient($key)
	{
		return get_site_transient($this->formatTransientKey($key));
	}

	/**
	 * Set/update the value of a transient.
	 *
	 * @see /reference/functions/set_transient/
	 * @access protected
	 * @param string $key
	 * @param mixed $value
	 * @param int $ttl
	 * @return bool
	 */
	protected function setTransient($key, $value = 1, $ttl = 300)
	{
		return set_transient($this->formatTransientKey($key),$value,$ttl);
	}

	/**
	 * Set/update the value of a site transient.
	 *
	 * @see /reference/functions/set_site_transient/
	 * @access protected
	 * @param string $key
	 * @param mixed $value
	 * @param int $ttl
	 * @return bool
	 */
	protected function setSiteTransient($key, $value = 1, $ttl = 300)
	{
		return set_site_transient($this->formatTransientKey($key),$value,$ttl);
	}

	/**
	 * Deletes a transient.
	 *
	 * @see /reference/functions/delete_transient/
	 * @access protected
	 * @param string $key
	 * @return bool
	 */
	protected function deleteTransient($key)
	{
		return delete_transient($this->formatTransientKey($key));
	}

	/**
	 * Deletes a site transient.
	 *
	 * @see /reference/functions/delete_site_transient/
	 * @access protected
	 * @param string $key
	 * @return bool
	 */
	protected function deleteSiteTransient($key)
	{
		return delete_site_transient($this->formatTransientKey($key));
	}

	/**
	 * Deletes all transients (Under namespace).
	 *
	 * @access protected
	 * @param string $name
	 * @return bool
	 */
	protected function deleteTransients($name = null)
	{
		if ( empty($prefix = $this->getNameSpace()) ) {
			return false;
		}
		if ( $name ) {
			$prefix = "{$prefix}_{$name}";
		}
		$db = new Orm();
		$query = "DELETE FROM {$db->prefix}options WHERE `option_name` LIKE '%_transient_{$prefix}%';";
		return (bool)$db->query($query);
	}

	/**
	 * Deletes all site transients (Under namespace).
	 *
	 * @access protected
	 * @param void
	 * @return bool
	 */
	protected function deleteSiteTransients($name = null)
	{
		if ( empty($prefix = $this->getNameSpace()) ) {
			return false;
		}
		if ( $name ) {
			$prefix = "{$prefix}-{$name}";
		}
		$db = new Orm();
		$query = "DELETE FROM {$db->prefix}options WHERE `option_name` LIKE '%_site_transient_{$prefix}%';";
		return (bool)$db->query($query);
	}

	/**
	 * Check if is plugin namespace.
	 *
	 * @access protected
	 * @param string $slug null
	 * @return bool
	 */
	protected function isPluginAdmin($slug = null)
	{
		$protocol = Server::getProtocol();
		$host = Server::get('HTTP_HOST');
		$request = Server::get('REQUEST_URI');
		$url = "{$protocol}{$host}{$request}";
		$current = ($slug) ? $slug : "?page={$this->getNameSpace()}";
		if ( Stringify::contains($url, $current) ) {
			return true;
		}
		return false;
	}

	/**
	 * Get site locale.
	 *
	 * @access public
	 * @inheritdoc
	 */
	public function getLocale($user = null) : string
	{
		return Localization::getLocale($user);
	}

	/**
	 * Get plugin language.
	 *
	 * @access protected
	 * @return string
	 */
	protected function getLang(bool $translator = false) : string
	{
		// Get from system
		$lang = $default = $this->getLocale();

		// Get from third-party
		if ( $translator || $this->hasMultilingual() ) {
			$lang = $this->getTranslatorLocale();
		}

		// Get from default
		if ( !$lang ) {
			$lang = $default;
		}

		// Normalize
		return $this->normalizeLocale($lang);
	}

	/**
	 * Return plugin header using file,
	 * file : {pluginDir}/{pluginMain}.php.
	 *
	 * @access protected
	 * @param string $file
	 * @return mixed
	 */
	protected function getPluginHeader($file = '')
	{
	    if ( !TypeCheck::isFunction('get_plugin_data') ) {
	        require_once(GlobalConst::rootDir('wp-admin/includes/plugin.php'));
	    }
	    $file = GlobalConst::pluginDir($file);
		return get_plugin_data($file);
	}

	/**
	 * Return plugin data using file,
	 * file : {pluginDir}/{pluginMain}.php.
	 *
	 * @access protected
	 * @param string $file
	 * @param array $header
	 * @param bool $context
	 * @return array
	 */
	protected function getPluginData($file = '', $header = [], $context = false)
	{
		if ( empty($header) ) {
			$header['version'] = 'Version';
		}
		$file = GlobalConst::pluginDir($file);
		return get_file_data($file,$header,$context);
	}

	/**
	 * Return plugin status,
	 * {pluginDir}/{pluginMain}.php.
	 *
	 * @access protected
	 * @param string $file {pluginDir}/{pluginMain}.php
	 * @return bool
	 */
	protected function isPlugin($file = '')
	{
		if ( function_exists('is_plugin_active') ) {
			return is_plugin_active($file);
		} else {
			$plugins = $this->applyFilter('active_plugins',$this->getOption('active_plugins'));
			return in_array($file,$plugins);
		}
		return false;
	}

	/**
	 * Return plugin version status,
	 * {pluginDir}/{pluginMain}.php.
	 *
	 * @access protected
	 * @param string $file {pluginDir}/{pluginMain}.php
	 * @param string $version
	 * @return bool
	 */
	protected function isPluginVersion($file = '', $version = '')
	{
		if ( $this->isPlugin($file) ) {
			$data = $this->getPluginData($file);
			return $this->versionCompare($data['version'],$version,'>=');
		}
		return false;
	}

	/**
	 * Return plugin class exists.
	 *
	 * @access protected
	 * @param string $callable
	 * @return bool
	 */
	protected function isPluginClass($callable)
	{
		$callable = Stringify::replace('/','\\',$callable);
		if ( Stringify::contains($callable,'\\') ) {
			if ( !File::exists($this->getPluginDir("{$callable}.php")) ) {
				return false;
			}
		}
		return TypeCheck::isClass($callable);
	}

	/**
	 * Add plugin capability.
	 *
	 * @access protected
	 * @param string $role
	 * @param string $cap
	 * @return void
	 */
	protected function addPluginCapability($role, $cap = 'manage')
	{
		$prefix = Stringify::replace('-','_',$this->getNameSpace());
		$this->addCapability($role,"{$cap}_{$prefix}");
	}

	/**
	 * Add plugin capabilities.
	 *
	 * @access protected
	 * @param mixed $roles
	 * @param string $cap
	 * @return void
	 */
	protected function addPluginCaps($roles, $cap = 'manage')
	{
		if ( TypeCheck::isArray($roles) ) {
			foreach ($roles as $role) {
				$this->addPluginCapability($role,$cap);
			}
		} else {
			$this->addPluginCapability($roles,$cap);
		}
	}

	/**
	 * Check plugin capability.
	 *
	 * @access protected
	 * @param string $cap
	 * @return void
	 */
	protected function hasPluginCapability($cap = 'manage')
	{
		$prefix = Stringify::replace('-', '_', $this->getNameSpace());
		$this->hasCapability("{$cap}_{$prefix}");
	}

	/**
	 * Remove plugin capability.
	 *
	 * @access protected
	 * @param string $role
	 * @param string $cap
	 * @return void
	 */
	protected function removePluginCapability($role, $cap = 'manage')
	{
		$prefix = Stringify::replace('-','_',$this->getNameSpace());
		$this->removeCapability($role,"{$cap}_{$prefix}");
	}

	/**
	 * Remove plugin capabilities.
	 *
	 * @access protected
	 * @param mixed $roles
	 * @param string $cap
	 * @return void
	 */
	protected function removePluginCaps($roles, $cap = 'manage')
	{
		if ( TypeCheck::isArray($roles) ) {
			foreach ($roles as $role) {
				$this->removePluginCapability($role,$cap);
			}
		} else {
			$this->removePluginCapability($roles,$cap);
		}
	}

	/**
	 * @access protected
	 * @param string $haystack
	 * @param mixed $needle
	 * @return bool
	 */
	protected function hasScript($haystack, $needle)
	{
	    if ( !TypeCheck::isArray($needle) ) {
	    	$needle = [$needle];
	    }
	    foreach ($needle as $search) {
	        if ( Stringify::contains($haystack,$search) ) {
	        	return true;
	        }
	    }
	    return false;
	}

	/**
	 * Set HTTP response,
	 * Including translated message.
	 * 
	 * @access protected
	 * @param mixed $message
	 * @param array $content
	 * @param string $status
	 * @param int $code
	 * @return void
	 */
	protected function setResponse($message, $content = [], $status = 'success', $code = 200)
	{
		if ( TypeCheck::isArray($message) && count($message) == 2 ) {
		  	$temp = $message[0];
		  	$args = (array)$message[1];
		  	$message = $this->translateVar($temp,$args);
		  	
		} else {
			$message = $this->translate($message);
		}
		$this->setHttpResponse($message, $content, $status, $code);
	}

	/**
	 * Set HTTP response without translating message.
	 * 
	 * @access protected
	 * @param mixed $message
	 * @param array $content
	 * @param string $status
	 * @param int $code
	 * @return void
	 */
	protected function setHttpResponse($message, $content = [], $status = 'success', $code = 200)
	{
		Response::set($message, $content, $status, $code);
	}

	/**
	 * Compare versions.
	 *
	 * @access public
	 * @param string $version1
	 * @param string $version2
	 * @param string $operator
	 * @return bool
	 */
	public function versionCompare($version1, $version2, $operator = '==')
	{
		return version_compare($version1,$version2,$operator);
	}

	/**
	 * Load plugin text domain.
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function localize()
	{
		$domain   = $this->getNameSpace();
		$path     = $this->applyNamespace('languages', '/');
		$override = $this->getThemeDir($domain);
		$override = $this->applyPluginFilter('translation-path', $override);

        if ( File::isDir($override) ) {
			$file = Localization::parse($domain, $this->getLocale());
			$file = "{$override}{$file}";
			if ( File::isFile($file) ) {
				Localization::load($domain, $file);
			}

        } else {
        	Localization::loadPlugin($domain, $path);
        }
	}

	/**
	 * Translate string,
	 * May require quotes escaping.
	 *
	 * @access public
	 * @param mixed $string
	 * @return mixed
	 */
	public function translate($string = '')
	{
		return __((string)$string, $this->getNameSpace());
	}

	/**
	 * Translate string including variables,
	 * May require quotes escaping.
	 *
	 * @access public
	 * @param mixed $string
	 * @param mixed $vars
	 * @return mixed
	 */
	public function translateVar($string = '', $vars = null)
	{
		if ( TypeCheck::isArray($vars) ) {
			return vsprintf($this->translate($string), $vars);
		}

		if ( TypeCheck::isString($vars) ) {
			$vars = Stringify::replaceRegex('/\s+/', $this->translate('{Empty}'), $vars);
			$string = Stringify::replace($vars, '%s', $string);
			return sprintf($this->translate($string), $vars);
		}

		return $string;
	}

	/**
	 * Translate string (Alias).
	 *
	 * @access public
	 * @param mixed $string
	 * @return string
	 */
	public function trans(?string $string) : string
	{
		return $this->translate((string)$string);
	}

	/**
	 * Translate string with variables (Alias).
	 *
	 * @access public
	 * @param string $string
	 * @param mixed $vars
	 * @return string
	 */
	public function transVar(?string $string, $vars = null) : string
	{
		return $this->translateVar((string)$string, $vars);
	}

	/**
	 * Translate deep strings.
	 *
	 * @access public
	 * @param array $strings
	 * @return array
	 */
	public function translateDeep($strings)
	{
		Arrayify::walkRecursive($strings, function(&$string) {
			if ( TypeCheck::isString($string) ) {
				$string = $this->translate($string);
			}
		});
		return $strings;
	}

	/**
	 * Load plugin translated strings.
	 * Admin and Front.
	 *
	 * @access public
	 * @param string $type
	 * @return array
	 */
	public function loadStrings(?string $type = null) : array
	{
		return $this->translateDeep(
			$this->getStrings($type)
		);
	}

	/**
	 * Get current screen.
	 *
	 * @access public
	 * @param void
	 * @return object
	 */
	public function getCurrentScreen()
	{
		return get_current_screen();
	}

	/**
	 * Check is current screen.
	 *
	 * @access public
	 * @param string $screen
	 * @return bool
	 */
	public function isCurrentScreen($screen = null)
	{
		$screen = ($screen) ? $screen : "toplevel_page_{$this->getNameSpace()}";
		$current = $this->getCurrentScreen();
		if ( $current->base == $screen ) {
			return true;
		}
		return false;
	}

	/**
	 * Add help tab.
	 *
	 * @access public
	 * @param array $settings
	 * @return void
	 */
	public function addHelpTab($settings)
	{
		$this->getCurrentScreen()->add_help_tab($settings);
	}

	/**
	 * Set help sidebar.
	 *
	 * @access public
	 * @param string $html
	 * @return void
	 */
	public function setHelpSidebar($html)
	{
		$this->getCurrentScreen()->set_help_sidebar($html);
	}

	/**
	 * Set help sidebar.
	 *
	 * @access public
	 * @param object $bar
	 * @param array $settings
	 * @return void
	 */
	public function addMenu($bar, $settings = [])
	{
		$bar->add_menu($settings);
	}

	/**
	 * Check action token.
	 *
	 * @access public
	 * @param mixed $action
	 * @param bool $strict
	 * @return mixed
	 */
	public function checkToken($action = -1, $strict = false)
	{
		$nonce = HttpRequest::isSetted('nonce') ? HttpRequest::get('nonce') : false;
	    if ( !$this->checkNonce($nonce,$action) ) {
	    	if ( $strict ) {
	    		die($this->translate('Invalid token'));
	    	}
	    	$code = ($this->isDebug()) ? 400 : 200;
	    	$this->setResponse('Invalid token',[],'error',$code);
	    }
	}

	/**
	 * Check Ajax referer.
	 *
	 * @access public
	 * @param mixed $action
	 * @param mixed $arg, Query arg
	 * @param bool $strict
	 * @return mixed
	 */
	public function checkAjaxReferer($action = -1, $arg = 'nonce', $strict = false)
	{
	  	if ( !check_ajax_referer($action,$arg,false) ) {
	    	if ( $strict ) {
	    		die($this->translate('Invalid token'));
	    	}
	    	$code = ($this->isDebug()) ? 400 : 200;
	    	$this->setResponse('Invalid token',[],'error',$code);
	  	}
	}

	/**
	 * Check nonce.
	 *
	 * @access public
	 * @param string $nonce
	 * @param mixed $action
	 * @return bool
	 */
	public function checkNonce($nonce = '', $action = -1)
	{
	  	return wp_verify_nonce($nonce,$action);
	}

	/**
	 * Create nonce.
	 *
	 * @access public
	 * @param mixed $action
	 * @return string
	 */
	public function createNonce($action = -1)
	{
	  	return wp_create_nonce($action);
	}

	/**
	 * Get plugin multiling status.
	 *
	 * @access public
	 * @return bool
	 */
	public function hasMultilingual() : bool
	{
		return ($this->isMultilingual() && $this->hasTranslator());
	}

	/**
	 * Check whether translator is active.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function hasTranslator() : bool
	{
		return (bool)Translator::isActive();
	}

	/**
	 * Get translator locale.
	 *
	 * @access protected
	 * @inheritdoc
	 */
	protected function getTranslatorLocale() : string
	{
		return (string)Translator::getLocale();
	}

	/**
	 * Normalize locale.
	 *
	 * @access protected
	 * @param string $locale
	 * @return string
	 */
	protected function normalizeLocale(string $locale) : string
	{
		$lang   = Localization::parseLang($locale);
		$region = Localization::parseRegion($locale);

		if ( $lang === $region ) {
			return $lang;
		}
		
		return Localization::normalizeLocale($locale);
	}

	/**
	 * Set options language.
	 *
	 * @access public
	 * @param mixed $lang
	 * @return mixed
	 */
	public function getOptionLang(string $key, bool $lang)
	{
		if ( $this->hasMultilingual() && $lang ) {
			$key = "{$key}-{$this->getLang()}";
		}
		return $this->applyPrefix($key);
	}

	/**
	 * Format transient key.
	 *
	 * @access private
	 * @param string $key
	 * @return mixed
	 */
	private function formatTransientKey($key)
	{
		if ( $this->hasPluginFilter('transient-key-format') ) {
			$key = $this->applyPluginFilter('transient-key-format',$key);
		}
		$key = Stringify::slugify($key);
		return substr("{$this->getNameSpace()}-{$key}", 0, 172);
	}
}
