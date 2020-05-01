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

namespace VanillePlugin\lib;

use VanillePlugin\lib\PluginOptions;
use VanillePlugin\inc\Post;
use VanillePlugin\inc\Get;
use VanillePlugin\int\AjaxInterface;
use VanillePlugin\int\AdminAjaxInterface;

class Ajax extends PluginOptions implements AjaxInterface
{
	/**
	 * @access private
	 * @var array $callable
	 * @var array $actions
	 */
	private $callable = [];
	private $actions = [];

	/**
	 * Ajax Controller
	 *
	 * @param object $callable
	 * @return void
	 *
	 * action : wp_ajax_{namespace}-{action}
	 * action : wp_ajax_nopriv_{namespace}-{action}
	 */
	public function __construct(AdminAjaxInterface $callable)
	{
		// Init plugin config
		$this->initConfig();

		// Set vars
		$this->callable = $callable;
		$this->actions = $this->getAjax();

		// Add hooks
		foreach ($this->actions as $action) {
			$this->addAction("wp_ajax_{$this->getNameSpace()}-{$action}", [$this,'callback']);
			$this->addAction("wp_ajax_nopriv_{$this->getNameSpace()}-{$action}", [$this,'callback']);
		}
	}

	/**
	 * AjaxCallback react as Ajax Controller
	 *
	 * @access public
	 * @param void
	 * @return void
	 *
	 * use : isAction to separate actions
	 */
	public function callback()
	{
		foreach ($this->actions as $action) {
			while ( $this->isAction($action) ) {
				$this->callable->$action();
				exit();
			}
		}
		die();
	}

	/**
	 * Check required action
	 * Supports both Post & Get method
	 *
	 * @access public
	 * @param string $action
	 * @return boolean
	 */
	public function isAction($action)
	{
		if ( Post::isSetted('action') ) {
			if (Post::get('action') == "{$this->getNameSpace()}-{$action}") {
				return true;
			}
		} elseif ( Get::isSetted('action') ) {
			if (Get::get('action') == "{$this->getNameSpace()}-{$action}") {
				return true;
			}
		}
		return false;
	}
}