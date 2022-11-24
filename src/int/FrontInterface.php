<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.3
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface FrontInterface
{
    /**
     * Setup front.
     *
     * @param ShortcodeInterface $shortcode
     * @see !isAdmin()
     */
    function __construct(ShortcodeInterface $shortcode = null);

    /**
     * Init plugin front.
     * Action: wp
     * 
     * @param void
     * @return void
     * @see !AMP::isActive()
     */
    function init();

    /**
     * Add front plugin CSS.
     * Action: wp_enqueue_scripts
     * 
     * @param void
     * @return void
     */
    function initCSS();

    /**
     * Add front plugin JS.
     * Action: wp_enqueue_scripts
     * 
     * @param void
     * @return void
     */
    function initJS();

    /**
     * Add front body class.
     * Action: body_class
     * 
     * @param array $classes
     * @return array
     */
    function addClass($classes);
}
