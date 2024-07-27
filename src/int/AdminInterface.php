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

interface AdminInterface
{
    /**
     * Admin setup.
     *
     * @param MenuInterface $menu
     * @param SettingsInterface $settings
     */
    function __construct(MenuInterface $menu = null, SettingsInterface $settings = null);

    /**
     * Init admin.
     *
     * @return void
     */
    function init();

    /**
     * Add admin CSS.
     *
     * @return void
     */
    function initCSS();

    /**
     * Add admin JS.
     *
     * @return void
     */
    function initJS();

    /**
     * Add global admin CSS.
     *
     * @return void
     */
    function globalCSS();

    /**
     * Add global admin JS.
     *
     * @return void
     */
    function globalJS();

    /**
     * Add admin body class.
     * 
     * @param string $classes
     * @return string
     */
    function addClass($classes = '');
}
