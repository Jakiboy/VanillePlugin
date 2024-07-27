<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\inc\{
    Arrayify, GlobalConst, Stringify, TypeCheck
};
use VanillePlugin\int\CronInterface;

/**
 * Plugin CRON manager.
 */
class Cron extends PluginOptions implements CronInterface
{
	/**
	 * @access protected
	 * @var string SERVER, Server constant
	 */
	protected const SERVER = 'disable-wp-cron';

	/**
	 * @access protected
	 * @var array $schedules, Cron custom schedules
	 * @var array $events, Cron events
	 * @var int $timestamp, Cron timestamp
	 */
	protected $schedules = [];
	protected $events = [];
	protected $timestamp;

	/**
	 * @inheritdoc
	 */
	public function __construct(?int $timestamp = null)
	{
		$this->timestamp = $timestamp ?: time();
	}

	/**
	 * @inheritdoc
	 */
	public function register()
	{
		$this->addFilter('cron-schedules', function($schedules) {
			$custom = $this->sanitizeSchedules(
				$this->getSchedules()
			);
			return Arrayify::merge($custom, $schedules);
		});

		$events = $this->sanitizeEvents(
			$this->getEvents()
		);

		foreach ($events as $event) {
			$name = $this->applyNameSpace($event['name']);
			$this->addAction($name, $event['callback']);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		if ( GlobalConst::installing() ) {
			return;
		}

		$events = $this->sanitizeEvents(
			$this->getEvents()
		);

		foreach ($events as $event) {
			$name = $this->applyNameSpace($event['name']);
			if ( !$this->next($name) ) {
				$this->schedule($event['schedule'], $name);
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function remove()
	{
		$events = $this->sanitizeEvents(
			$this->getEvents()
		);

		foreach ($events as $event) {
			$name = $this->applyNameSpace($event['name']);
			$this->clear($name);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function useServer()
	{
		$const = Stringify::undash(static::SERVER, true);
		if ( !defined($const) ) {
			define($const, true);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function isServer() : bool
	{
		$const = Stringify::undash(static::SERVER, true);
		return defined($const);
	}

	/**
	 * @inheritdoc
	 */
	public function next(string $name, array $args = []) : int
	{
		return (int)wp_next_scheduled($name, $args);
	}

	/**
	 * @inheritdoc
	 */
	public function schedule(string $interval, string $hook, array $args = []) : bool
	{
		return wp_schedule_event($this->timestamp, $interval, $hook, $args, false);
	}

	/**
	 * @inheritdoc
	 */
	public function once(string $hook, array $args = []) : bool
	{
		return wp_schedule_single_event($this->timestamp, $hook, $args, false);
	}

	/**
	 * @inheritdoc
	 */
	public function unschedule(string $hook, array $args = []) : bool
	{
		return wp_unschedule_event($this->timestamp, $hook, $args);
	}

	/**
	 * @inheritdoc
	 */
	public function clear(string $hook, array $args = []) : int
	{
		return (int)wp_clear_scheduled_hook($hook, $args);
	}

	/**
	 * Sanitize cron event.
	 *
	 * @access protected
	 * @param array $events
	 * @return array
	 */
	protected function sanitizeEvents(array $events) : array
	{
		foreach ($events as $key => $event) {

			if ( !isset($event['callback'])  ) {
				$callback = Stringify::camelcase($event['name']);

				if ( TypeCheck::hasMethod(static::class, $callback) ) {
					$events[$key]['callback'] = [$this, $callback];

				} else {
					unset($events[$key]);
					continue;
				}
			}

		}

		return $events;
	}

	/**
	 * Sanitize event schedules.
	 *
	 * @access protected
	 * @param array $schedules
	 * @return array
	 */
	protected function sanitizeSchedules(array $schedules) : array
	{
		foreach ($schedules as $name => $data) {
			$display = $data['display'] ?? false;
			if ( !$display ) {
				$display = Stringify::capitalize($name);
			}
			$display = $this->translate($display);
			$schedules[$name]['display'] = $display;
		}

		return $schedules;
	}
}
