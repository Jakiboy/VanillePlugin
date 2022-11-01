<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.1
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\inc\GlobalConst;
use VanillePlugin\inc\Stringify;

/**
 * Wrapper Class for Advanced WordPress Global Functions,
 * Defines Only Base Functions Used by Plugins.
 * 
 * @see https://developer.wordpress.org/
 */
class WordPress
{
	/**
	 * Set the activation hook for a plugin.
	 *
	 * @access protected
	 * @param string $file
	 * @param callable $method
	 * @return void
	 */
	protected function registerActivation($file, $method)
	{
		register_activation_hook($file,$method);
	}

	/**
	 * Set the deactivation hook for a plugin.
	 *
	 * @access protected
	 * @param string $file
	 * @param callable $method
	 * @return void
	 */
	protected function registerDeactivation($file, $method)
	{
		register_deactivation_hook($file,$method);
	}

	/**
	 * Set the uninstallation hook for a plugin,
	 * use class name instead of object ['Plugin','uninstall'].
	 *
	 * @access protected
	 * @param string $file
	 * @param callable $method
	 * @return void
	 */
	protected function registerUninstall($file, $method)
	{
		if ( $this->isAdmin() ) {
			register_uninstall_hook($file,$method);
		}
	}
	
	/**
	 * Register a shortcode handler.
	 *
	 * @access protected
	 * @param string $tag
	 * @param callable $callback
	 * @return void
	 */
	protected function addShortcode($tag, $callback)
	{
		add_shortcode($tag,$callback);
	}

	/**
	 * Search content for shortcodes,
	 * and filter shortcodes through their hooks.
	 *
	 * @access protected
	 * @param string $content
	 * @param bool $ignore, Ignore HTML false
	 * @return void
	 */
	protected function renderShortcode($content, $ignore = false)
	{
		echo $this->doShortcode($content,$ignore);
	}

	/**
	 * Search content for shortcodes,
	 * and filter shortcodes through their hooks.
	 *
	 * @access protected
	 * @param string $content
	 * @param bool $ignore, Ignore HTML false
	 * @return string
	 */
	protected function doShortcode($content, $ignore = false)
	{
		return do_shortcode($content,$ignore);
	}

	/**
	 * Removes hook for shortcode.
	 *
	 * @access protected
	 * @param string $tag
	 * @return void
	 */
	protected function removeShortcode($tag)
	{
		remove_shortcode($tag);
	}

	/**
	 * Checks Whether a registered shortcode exists named $tag.
	 *
	 * @access protected
	 * @param string $tag
	 * @return bool
	 */
	protected function shortcodeExists($tag)
	{
		return shortcode_exists($tag);
	}

	/**
	 * Hook a method on a specific action.
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $method
	 * @param int $priority
	 * @param int $args
	 * @return true
	 */
	protected function addAction($hook, $method, $priority = 10, $args = 1)
	{
		switch ( Stringify::lowercase($hook) ) {
			case 'head':
				$hook = 'wp_head';
				break;
			case 'body':
				$hook = 'wp_body_open';
				break;
			case 'footer':
				$hook = 'wp_footer';
				break;
			case 'content':
				$hook = 'the_content';
				break;
		}
		return add_action($hook,$method,$priority,$args);
	}

	/**
	 * Remove a method from a specified action hook.
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $method
	 * @param int $priority
	 * @return bool
	 */
	protected function removeAction($hook, $method, $priority = 10)
	{
		switch ( Stringify::lowercase($hook) ) {
			case 'head':
				$hook = 'wp_head';
				break;
			case 'body':
				$hook = 'wp_body_open';
				break;
			case 'footer':
				$hook = 'wp_footer';
				break;
			case 'content':
				$hook = 'the_content';
				break;
		}
		return remove_action($hook,$method,$priority);
	}

	/**
	 * Add a method from a specified action hook.
	 *
	 * @access protected
	 * @param string $tag
	 * @param mixed $args
	 * @return void
	 */
	protected function doAction($tag, $args = null)
	{
		do_action($tag,$args);
	}

	/**
	 * Hook a function or method to a specific filter action.
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $method
	 * @param int $priority
	 * @param int $args
	 * @return true
	 */
	protected function addFilter($hook, $method, $priority = 10, $args = 1)
	{
		switch ( Stringify::lowercase($hook) ) {
			case 'head':
				$hook = 'wp_head';
				break;
			case 'body':
				$hook = 'wp_body_open';
				break;
			case 'footer':
				$hook = 'wp_footer';
				break;
			case 'content':
				$hook = 'the_content';
				break;
		}
		return add_filter($hook,$method,$priority,$args);
	}

	/**
	 * Remove a function from a specified filter hook.
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $method
	 * @param int $priority
	 * @return bool
	 */
	protected function removeFilter($hook, $method, $priority = 10)
	{
		return remove_filter($hook,$method,$priority);
	}

	/**
	 * Calls the callback functions,
	 * that have been added to a filter hook.
	 *
	 * @access protected
	 * @param string $hook
	 * @param mixed $value
	 * @param mixed $args
	 * @return mixed
	 */
	protected function applyFilter($hook, $value, $args = null)
	{
		return apply_filters($hook,$value,$args);
	}

	/**
	 * Check if any filter has been registered for a hook.
	 *
	 * @access protected
	 * @param string $hook
	 * @param callable $method
	 * @return bool
	 */
	protected function hasFilter($hook, $method = false)
	{
		return has_filter($hook,$method);
	}

	/**
	 * Register and Enqueue a CSS stylesheet.
	 *
	 * @access protected
	 * @param string $id
	 * @param string $path
	 * @param mixed $deps
	 * @param mixed $version
	 * @param string $media
	 * @return void
	 */
	protected function addCSS($id, $path, $deps = [], $version = false, $media = 'all')
	{
		if ( !Stringify::contains($path,'http') ) {
		    $path = $this->getPluginUrl($path);
		}
		wp_register_style($id,$path,$deps,$version,$media);
		wp_enqueue_style($id);
	}

	/**
	 * Register and Enqueue a new script.
	 *
	 * @access protected
	 * @param string $id
	 * @param string $path
	 * @param mixed $deps
	 * @param mixed $version
	 * @param string $footer
	 * @return void
	 */
	protected function addJS($id, $path, $deps = [], $version = false, $footer = false)
	{
		if ( !Stringify::contains($path,'http') ) {
		    $path = $this->getPluginUrl($path);
		}
		wp_register_script($id,$path,$deps,$version,$footer);
		wp_enqueue_script($id);
	}

	/**
	 * Determines whether a script has been added to the queue.
	 *
	 * @access protected
	 * @param string $id
	 * @param string $list
	 * @return bool
	 */
	protected function isJS($id, $list = 'enqueued')
	{
		if ( wp_script_is($id,$list)) {
			return true;
		}
		return false;
	}

	/**
	 * Remove a previously enqueued and registered CSS stylesheet.
	 *
	 * @access protected
	 * @param string $id 
	 * @return void
	 */
	protected function removeCSS($id)
	{
		wp_dequeue_style($id);
		wp_deregister_style($id);
	}

	/**
	 * Remove a previously enqueued and registered script.
	 *
	 * @access protected
	 * @param string $id 
	 * @return void
	 */
	protected function removeJS($id)
	{
		wp_dequeue_script($id);
		wp_deregister_script($id);
	}

	/**
	 * Localize a script,
	 * Works on already added script only.
	 *
	 * @access protected
	 * @param string $id
	 * @param object $object
	 * @param array $content
	 * @return bool
	 */
	protected function localizeJS($id, $object, $content = [])
	{
		wp_localize_script($id,$object,$content);
	}

	/**
	 * Register a settings and its data.
	 *
	 * @access protected
	 * @param string $group
	 * @param string $option
	 * @param array $args
	 * @return void
	 */
	protected function registerOption($group, $option, $args = [])
	{
		register_setting($group,$option,$args);
	}

	/**
	 * Register a settings and its data.
	 *
	 * @access protected
	 * @param string $option
	 * @param mixed $value
	 * @return inherit
	 */
	protected function addOption($option, $value)
	{
		return add_option($option, Stringify::serialize($value));
	}

	/**
	 * Retrieves an option value based on an option name.
	 *
	 * @access protected
	 * @param string $option
	 * @param string $default
	 * @return mixed
	 */
	protected function getOption($option, $default = null)
	{
		$option = Stringify::unserialize(get_option($option,$default));
		return Stringify::slashStrip($option);
	}

	/**
	 * Update the value of an option that was already added.
	 *
	 * @access protected
	 * @param string $option
	 * @param mixed $value
	 * @return inherit
	 */
	protected function updateOption($option, $value)
	{
		return update_option($option,$value);
	}

	/**
	 * Removes option by name.
	 *
	 * @access protected
	 * @param string $option
	 * @return inherit
	 */
	protected function removeOption($option)
	{
		return delete_option($option);
	}

	/**
	 * Add a top-level menu page.
	 *
	 * @access protected
	 * @param string $title
	 * @param string $menu
	 * @param string $cap capability
	 * @param string $slug
	 * @param callable $cb callback
	 * @param string $icon
	 * @param bool $i custom icon
	 * @param int $p position
	 * @return string
	 */
	protected function addMenuPage($title, $menu, $cap, $slug, $cb, $icon = 'admin-plugins', $i = false, $p = 20)
	{
		if ( $i ) {
			$icon = "dashicons-{$icon}";
		}
		return add_menu_page($title,$menu,$cap,$slug,$cb,$icon,$p);
	}

	/**
	 * Add a top-level menu page.
	 *
	 * @access protected
	 * @param string $parent
	 * @param string $title
	 * @param string $menu
	 * @param string $cap
	 * @param string $slug
	 * @param callable $cb callback
	 * @return inherit
	 */
	protected function addSubMenuPage($parent, $title, $menu, $cap, $slug, $cb)
	{
		return add_submenu_page($parent,$title,$menu,$cap,$slug,$cb);
	}

	/**
	 * Add a top-level menu page.
	 *
	 * @access protected
	 * @param string $title
	 * @param string $menu
	 * @param string $cap
	 * @param string $slug
	 * @param callable $callable
	 * @return inherit
	 */
	protected function addOptionPage($title, $menu, $cap, $slug, $method)
	{
		return add_options_page($title,$menu,$cap,$slug,$method);
	}

	/**
	 * Add Metabox.
	 *
	 * @access protected
	 * @param string $id
	 * @param string $title
	 * @param callable $cb callback
	 * @param mixed $screen
	 * @param string $context
	 * @param string $p priority
	 * @param array $args
	 * @return void
	 *
	 * action : add_meta_boxes
	 * action : add_meta_boxes_{type}
	 */
	protected function addMetabox($id, $title, $cb, $screen, $context = 'advanced', $p = 'high', $args = null)
	{
		add_meta_box($id,$title,$cb,$screen,$context,$p,$args);
	}

	/**
	 * Retrieves a URL within the plugins or mu-plugins directory.
	 *
	 * @access protected
	 * @param string $path
	 * @param string $plugin
	 * @return string
	 */
	protected function getPluginUrl($path = '', $plugin = '')
	{
		return plugins_url($path,$plugin);
	}

	/**
	 * Retrieves plugin directory.
	 *
	 * @access protected
	 * @param string $plugin
	 * @return string
	 */
	protected function getPluginDir($plugin = null)
	{
		return GlobalConst::pluginDir($plugin);
	}

	/**
	 * Retrieves current theme url.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getThemeUrl()
	{
		return get_stylesheet_directory_uri();
	}

	/**
	 * Retrieves current theme directory.
	 *
	 * @access protected
	 * @param void
	 * @return string
	 */
	protected function getThemeDir()
	{
		return Stringify::formatPath(get_stylesheet_directory());
	}

	/**
	 * Check is WordPress Admin.
	 *
	 * @access protected
	 * @param void
	 * @return bool
	 */
	protected function isAdmin()
	{
		if ( is_admin() ) {
			return true;
		}
		return false;
	}

	/**
	 * Check user logged in.
	 *
	 * @access protected
	 * @param void
	 * @return bool
	 */
	protected function isLoggedIn()
	{
		return is_user_logged_in();
	}

	/**
	 * Check mobile.
	 *
	 * @access protected
	 * @param void
	 * @return bool
	 */
	protected function isMobile()
	{
		return wp_is_mobile();
	}

	/**
	 * Check user exists.
	 *
	 * @access protected
	 * @param mixed $user
	 * @param string $property
	 * @return bool
	 */
	protected function isUser($user, $property = 'username')
	{
		switch ( Stringify::lowercase($property) ) {
			case 'username':
				return username_exists(sanitize_user($user));
				break;
			case 'email':
				return email_exists($user);
				break;
			case 'id':
				$id = intval($user);
				$user = new \WP_User($id);
				return $user->exists();
				break;
		}
	}

	/**
	 * Get user permission.
	 *
	 * @access protected
	 * @param mixed $id null
	 * @param string $cap
	 * @param mixed $args
	 * @return bool
	 */
	protected function hasPermission($id = null, $cap = 'edit_posts', $args = [])
	{
		$id = ($id) ? intval($id) : get_current_user_id();
		return user_can($id,$cap,$args);
	}

	/**
	 * Get role.
	 *
	 * @access protected
	 * @param mixed $id null
	 * @return array
	 */
	protected function getRole($id = null)
	{
		$id = ($id) ? intval($id) : get_current_user_id();
		$user = new \WP_User($id);
		return (array)$user->roles;
	}

	/**
	 * Add role.
	 *
	 * @access protected
	 * @param string $display
	 * @param string $role null
	 * @param array $cap
	 * @return inherit
	 */
	protected function addRole($display, $role = null, $cap = [])
	{
		$role = ($role) ? $role : Stringify::slugify($display);
		$role = Stringify::replace('-','_',$role);
		return add_role($role,$display,$cap);
	}

	/**
	 * Remove role.
	 *
	 * @access protected
	 * @param string $role
	 * @return void
	 */
	protected function removeRole($role)
	{
		remove_role($role);
	}

	/**
	 * Add capability.
	 *
	 * @access protected
	 * @param string $role
	 * @param string $cap
	 * @param bool $grant true
	 * @return void
	 */
	protected function addCapability($role, $cap, $grant = true)
	{
		$role = get_role($role);
		$role->add_cap($cap,$grant);
	}

	/**
	 * Check capability.
	 *
	 * @access public
	 * @param string $cap
	 * @param mixed $args
	 * @return bool
	 */
	public function hadCapability($cap = 'edit_posts', $args = null)
	{
		return current_user_can($cap,$args);
	}

	/**
	 * Remove capability.
	 *
	 * @access protected
	 * @param string $role
	 * @param string $cap
	 * @return void
	 */
	protected function removeCapability($role, $cap)
	{
		$role = get_role($role);
		$role->remove_cap($cap);
	}

	/**
	 * Redirects to another page.
	 *
	 * @access protected
	 * @param string $location
	 * @param int $status 301
	 * @return void
	 */
	protected function redirect($location, $status = 301)
	{
		wp_redirect($location,$status);
		exit();
	}

	/**
	 * WordPress Authentication.
	 *
	 * @access protected
	 * @param string $username
	 * @param string $password
	 * @return mixed
	 */
	protected function authenticate($username = '', $password = '')
	{
		return wp_authenticate($username,$password);
	}

	/**
	 * Deactivate plugins.
	 *
	 * @access protected
	 * @param array $plugins
	 * @param bool $silent
	 * @return void
	 */
	protected function deactivatePlugins($plugins = [], $silent = true)
	{
		deactivate_plugins($plugins,$silent);
	}

	/**
	 * Check if Multisite is enabled.
	 *
	 * @access protected
	 * @param void
	 * @return bool
	 */
	protected function isMultisite()
	{
		return is_multisite();
	}
}
