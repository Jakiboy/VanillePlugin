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

interface ViewInterface
{
	/**
	 * Set extended view callables.
	 *
     * @param CallableInterface $callable
	 * @return void
	 */
	function setCallables(?CallableInterface $callable = null);

    /**
     * Render view.
     *
     * @param string $tpl
     * @param array $content
     * @param bool $end
     * @return void
     */
    function render(string $tpl = 'default', array $content = [], bool $end = false);

    /**
     * Aassign content to view.
     *
     * @param string $tpl
     * @param array $content
     * @return string
     */
    function assign(string $tpl = 'default', array $content = []) : string;
}
