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

namespace VanillePlugin\exc;

class ConfigException extends VanillePluginException
{
	public static function invalidConfigFile($file)
	{
		return "Couldn't find plugin configuration file: '{$file}'";
	}

    public static function invalidConfigFormat($file)
    {
        return "Invalid plugin configuration JSON format: '{$file}'";
    }

    public static function invalidConfig($error, $file)
    {
        return "Invalid plugin configuration: '{$error}' in '{$file}'";
    }
}
