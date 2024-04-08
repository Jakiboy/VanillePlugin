<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.6
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\exc;

class NamepsaceException extends VanillePluginException
{
	public static function invalidPluginNamepsace($namepsace)
	{
        $namepsace = ($namepsace) ? $namepsace : 'Undefined';
        return "Invalid plugin namepsace: '{$namepsace}'";
	}
}