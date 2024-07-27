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

namespace VanillePlugin\int;

interface RequirementInterface
{
	/**
	 * Check plugin paths.
	 * 
	 * @param void
	 * @return void
	 */
	function requirePath();

	/**
	 * Requires plugins.
	 * 
	 * @param void
	 * @return void
	 */
	function requirePlugins();

	/**
	 * Requires options.
	 * 
	 * @param void
	 * @return void
	 */
	function requireOptions();

	/**
	 * Requires templates.
	 * 
	 * @param void
	 * @return void
	 */
	function requireTemplates();

	/**
	 * Requires modules.
	 * 
	 * @param void
	 * @return void
	 */
	function requireModules();

	/**
	 * Requires PHP version.
	 * 
	 * @param void
	 * @return void
	 */
	function php();
}
