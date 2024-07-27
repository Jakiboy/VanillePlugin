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

use VanillePlugin\inc\{
	TypeCheck, Converter
};
use VanillePlugin\int\RestApiInterface;

/**
 * Wrapper class for RestAPI.
 */
abstract class AbstractRestAPI extends PluginOptions implements RestApiInterface
{
	/**
	 * @access protected
	 * @var object $auth Authentication
	 * @var string $version
	 * @var object $args
	 */
	protected $auth;
	protected $endpoint = 'default';
	protected $version = 'v1';
	protected $args = false;
	protected $override = false;

	/**
	 * @access private
	 * @var boolean $isOverridable
	 */
	private $isOverridable = false;

	/**
	 * Register routes
	 *
	 * @access public
	 * @param \WP_REST_Server $server
	 * @return void
	 */
	abstract public function registerRoutes(\WP_REST_Server $server);

	/**
	 * Init api hook.
	 *
	 * @access public
	 * @param string $method
	 * @return void
	 */
	public function init()
	{
		$this->addAction('rest_api_init',[$this,'registerRoutes']);
	}

	/**
	 * @access public
	 * @param bool $override
	 * @return void
	 */
	public function setOverride($override = false)
	{
		$this->override = $override;
	}

	/**
	 * @access public
	 * @param string $endpoint
	 * @return void
	 */
	public function setEndpoint($endpoint = 'default')
	{
		$this->endpoint = $endpoint;
	}

	/**
	 * @access public
	 * @param string $version
	 * @return void
	 */
	public function setVersion($version = 'v1')
	{
		$this->version = $version;
	}

	/**
	 * @access public
	 * @param object $args
	 * @return void
	 */
	public function addParameters($args = false)
	{
		if ( TypeCheck::isArray($args) ) {
			$this->args = Converter::toObject($args);

		} elseif ( TypeCheck::isObject($args) ) {
			$this->args = $args;
		}
	}

	/**
	 * @access public
	 * @param object $plugin
	 * @return void
	 */
	public function setAuthentication($plugin)
	{
		$this->auth = new Authentication($plugin);
	}

	/**
	 * @access protected
	 * @param string $route
	 * @param mixed $methods
	 * @param string $cb, Register callback
	 * @param string $p, Permission callback
	 * @return void
	 */
	protected function register($route, $methods = 'GET', $cb = 'defaultCallback', $p = 'isPermitted')
	{
		// Override default routes using custom plugin settings
		if ( $this->args ) {
			$route = !empty($this->args->{$cb}['route'])
			? $this->args->{$cb}['route'] : $route;
		}

		// Register rest route
	    register_rest_route("{$this->endpoint}/{$this->version}",$route, [
	        'methods'             => $methods,
	        'callback'            => [$this,$cb],
	        'permission_callback' => [$this,$p]
	    ], $this->isOverridable);
	}

	/**
	 * @access protected
	 * @param void
	 * @return true
	 */
	protected function defaultCallback()
	{
		return true;
	}

	/**
	 * @access protected
	 * @param array $args
	 * @return bool
	 */
	protected function isPermitted($args = [])
	{
		if ( $this->auth && $this->auth->isAllowed($args) ) {
         	return true;
		}
		return false;
	}
}
