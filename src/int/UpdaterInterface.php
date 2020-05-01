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

interface UpdaterInterface
{
    /**
     * @param string $hostUrl
     * @param array $params
     * @param boolean $forceUnsafe
     * @return void
     *
     * action : admin_init
     */
    function __construct($hostUrl, $params = [], $forceUnsafe = false);

    /**
     * @param void
     * @return void
     */
    function init();

    /**
     * @param object $transient
     * @return mixed
     */
    function check($transient);

    /**
     * @param object $transient
     * @param string $action
     * @param array $args
     * @return mixed
     */
    function infos($transient, $action, $args);
}