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

namespace VanillePlugin\exc;

class ShortcodeException extends VanillePluginException
{
    public static function invalidInstance() : string
    {
        return 'Invalid shortcode part instance';
    }
}
