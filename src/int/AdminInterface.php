<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.2
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface AdminInterface
{
    /**
     * Admin setup.
     *
     * @param MenuInterface $menu
     * @param SettingsInterface $settings
     * @see isAdmin()
     */
    function __construct(MenuInterface $menu = null, SettingsInterface $settings = null);

    /**
     * Add admin CSS.
     * 
     * @param void
     * @return void
     */
    function initCSS();

    /**
     * Add admin JS.
     * 
     * @param void
     * @return void
     */
    function initJS();

    /**
     * Add global admin CSS.
     * 
     * @param void
     * @return void
     */
    function globalCSS();

    /**
     * Add global admin JS.
     * 
     * @param void
     * @return void
     */
    function globalJS();

    /**
     * Override WordPress about and version.
     * 
     * @param void
     * @return void
     */
    function copyright();

    /**
     * Add admin body class.
     * 
     * @param string $classes
     * @return string
     */
    function addClass($classes = '');
}
