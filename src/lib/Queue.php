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

use VanillePlugin\inc\Stringify;

/**
 * Basic helper class for queuing requests.
 */
class Queue extends PluginOptions
{
	/**
	 * Add item to queue.
	 * 
	 * @access public
	 * @param string $item
	 * @param string $name
	 * @return bool
	 */
	public function add($item = '', $name = 'in')
	{
		$queue = $this->get($name);
		$queue[] = $item;
		return $this->set($queue,$name);
	}

	/**
	 * Check whether item exists.
	 * 
	 * @access public
	 * @param string $item
	 * @param string $name
	 * @return bool
	 */
	public function has($item = '', $name = 'in')
	{
		return Stringify::contains($this->get($name),$item) ;
	}

	/**
	 * Delete queue.
	 * 
	 * @access public
	 * @param string $name
	 * @return void
	 */
	public function delete($name = 'in')
	{
		return $this->setTransient("{$name}-queue",[]);
	}

	/**
	 * Get queue.
	 * 
	 * @access private
	 * @param string $name
	 * @return array
	 */
	private function get($name = 'in')
	{
		if ( !($queue = $this->getTransient("{$name}-queue")) ) {
			$queue = [];
			$this->set($queue,$name);
		}
		return $queue;
	}

	/**
	 * Set queue.
	 * 
	 * @access private
	 * @param array $value
	 * @param string $name
	 * @return bool
	 */
	private function set($value = [], $name = 'in')
	{
		return $this->setTransient("{$name}-queue",$value);
	}
}
