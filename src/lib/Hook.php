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

/**
 * Helper class for extra admin hooks (beta),
 * (Non multilingual).
 */
final class Hook extends PluginOptions
{
	/**
	 * @access public
	 * @var string FILTER
	 * @var string OPTION
	 */
	const FILTER = 'admin-extras';
	const OPTION = 'extras';

	/**
	 * Get valid hooks.
	 *
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public static function get()
	{
		$hook = parent::getStatic();

		// Filter settings
		if ( $hook->hasPluginFilter(static::FILTER) ) {

			$default = $hook->getPluginOption(static::OPTION, []);
			$extras  = $hook->applyPluginFilter(static::FILTER, $default);

			// Reset settings
			foreach ($extras as $type => $extra) {
				foreach ($extra as $key => $value) {
					if ( !isset($value['enable']) || $value['enable'] !== true ) {
						// Hook removed or not enabled
						unset($extras[$type][$key]);
						unset($default[$type][$key]);

					} else {
 						if ( isset($default[$type][$key]['value']) ) {
							// Hook registred
							$extras[$type][$key]['value'] = $default[$type][$key]['value'];
						}
					}
				}
			}

			// Return filtered extras
			$hook->updatePluginOption(static::OPTION, $default, false);
			return $extras;
		}

		return false;
	}

	/**
	 * Register hooks inside group.
	 *
	 * @access public
	 * @param string $group
	 * @return void
	 */
	public static function register($group)
	{
		$hook = parent::getStatic();
		$hook->registerPluginOption($group, static::OPTION,false);
	}

	/**
	 * Add default hooks if not exists.
	 *
	 * @access public
	 * @param array $default
	 * @return bool
	 */
	public static function add($default)
	{
		$hook = parent::getStatic();
		return $hook->addPluginOption(static::OPTION,$default,false);
	}
}
