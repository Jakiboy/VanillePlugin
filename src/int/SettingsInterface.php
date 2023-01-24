<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.5
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface SettingsInterface
{
    /**
     * Add plugin settings.
     *
     * @param void
     * @return void
     *
     * Template usage : {{ settingsFields('group') }}
     * Template usage : {{ settingsSections('group') }}
     * Template usage : {{ getOption('name') }}
     */
    function init();

    /**
     * Set default settings.
     *
     * @param void
     * @return void
     */
    static function setDefault();

    /**
     * Remove plugin settings.
     *
     * @param void
     * @return void
     */
    static function remove();
}
