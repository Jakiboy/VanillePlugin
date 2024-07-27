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
	HttpRequest, TypeCheck
};
use VanillePlugin\int\AjaxInterface;

/**
 * Wrapper class for Ajax inside plugins.
 * @see Front actions requires admin hook for authenticated users.
 */
final class Ajax extends PluginOptions
{
	/**
	 * @access private
	 * @var object $callable
	 * @var string $actions
	 */
	private $callable;
	private $actions = [];

	/**
	 * Init plugin Ajax.
	 *
	 * @param AjaxInterface $callable
	 */
	public function __construct(AjaxInterface $callable)
	{
		if ( !$this->isAjax() ) {
			return;
		}
		$this->callable = $callable;

		if ( $this->isAdminCallable() ) {
			$this->actions = $this->getAdminAjax();
			$this->initAdminActions();
			return;
		}

		if ( $this->isFrontCallable() ) {
			$this->actions = $this->getFrontAjax();
			$this->initFrontActions();
			return;
		}
	}

	/**
	 * Ajax action callback.
	 * [Uses: isAction()].
	 *
	 * @access public
	 * @return void
	 */
	public function callback()
	{
		foreach ($this->actions as $action) {
			if ( $this->isAction($action) ) {
				$this->callable->{$action}();
				break;
			}
		}
		die();
	}

	/**
	 * Init admin actions.
	 * [Action: wp_ajax_{namespace}-{action}].
	 *
	 * @access private
	 * @return void
	 */
	private function initAdminActions()
	{
		foreach ($this->actions as $action) {
			$action = "{$this->getNameSpace()}-{$action}";
			$this->addAction("wp_ajax_{$action}", [$this, 'callback']);
		}
	}

	/**
	 * Init front actions.
	 * [Action: wp_ajax_{namespace}-{action}].
	 * [Action: wp_ajax_nopriv_{namespace}-{action}].
	 *
	 * @access private
	 * @return void
	 */
	private function initFrontActions()
	{
		foreach ($this->actions as $action) {
			$action = "{$this->getNameSpace()}-{$action}";
			$this->addAction("wp_ajax_{$action}", [$this, 'callback']);
			$this->addAction("wp_ajax_nopriv_{$action}", [$this, 'callback']);
		}
	}

	/**
	 * Validate Ajax request action.
	 *
	 * @access private
	 * @return bool
	 */
	private function isAction(string $action) : bool
	{
		$action = "{$this->getNameSpace()}-{$action}";
		if ( HttpRequest::get('action') == $action ) {
			return true;
		}
		return false;
	}

	/**
	 * Check whether Ajax callable is admin.
	 *
	 * @access private
	 * @return bool
	 */
	private function isAdminCallable() : bool
	{
		return TypeCheck::hasInterface($this->callable, 'AdminAjaxInterface');
	}

	/**
	 * Check whether Ajax callable is front.
	 *
	 * @access private
	 * @return bool
	 */
	private function isFrontCallable() : bool
	{
		return TypeCheck::hasInterface($this->callable, 'FrontAjaxInterface');
	}
}
