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
 */

namespace VanillePlugin\int;

interface AjaxInterface
{
    /**
     * @param object $callable
     * @return void
     */
    function __construct($callable);
    
    /**
     * @param void
     * @return void
     */
    function callback();

    /**
     * @param string $action
     * @return boolean
     */
    function isAction($action);
}
