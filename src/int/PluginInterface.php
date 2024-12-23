<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface PluginInterface
{
	/**
	 * Plugin start action.
	 *
	 * @return void
	 */
	static function start();

	/**
	 * Plugin load action.
	 * [Action: plugins-loaded].
	 *
	 * @return void
	 */
	function load();

	/**
	 * Plugin activation action.
	 * [Action: activate_{pluginMain}.php].
	 *
	 * @return void
	 */
	function activate();

	/**
	 * Plugin deactivation action.
	 * [Action: deactivate_{pluginMain}.php].
	 *
	 * @return void
	 */
	function deactivate();

	/**
	 * Plugin upgrade action.
	 * [Action: upgrade-complete].
	 *
	 * @param object $upgrader
	 * @param array $options
	 * @return void
	 */
	function upgrade(object $upgrader, array $options);

	/**
	 * Add plugin action links.
	 * [Filter: plugin_action_links_{pluginMain}.php].
	 * [Filter: network_admin_plugin_action_links_{pluginMain}.php].
	 *
	 * @param array $links
	 * @return array
	 */
	function action(array $links) : array;

	/**
	 * Plugin uninstall action.
	 *
	 * @return void
	 */
	static function uninstall();
}
