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
 * Allowed to edit for plugin customization
 */

namespace VanillePlugin\int;

interface OrmInterface extends NameSpaceInterface
{
    /**
     * @param ConfigInterface $config
     * @param ShortcodeInterface $menu
     * @param SettingsInterface $settings
     * @return void
     */
	function select(OrmQueryInterface $data);

    /**
     * @param ConfigInterface $config
     * @param ShortcodeInterface $menu
     * @param SettingsInterface $settings
     * @return void
     */
	function count(OrmQueryInterface $data);

    /**
     * @param ConfigInterface $config
     * @param ShortcodeInterface $menu
     * @param SettingsInterface $settings
     * @return void
     */
	function insert($table, $data = [], $format = false);

    /**
     * @param ConfigInterface $config
     * @param ShortcodeInterface $menu
     * @param SettingsInterface $settings
     * @return void
     */
	function delete($table, $where = [], $format = null);

    /**
     * @param ConfigInterface $config
     * @param ShortcodeInterface $menu
     * @param SettingsInterface $settings
     * @return void
     */
	function deleteAll($table);
}
