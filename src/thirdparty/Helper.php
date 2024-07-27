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

namespace VanillePlugin\thirdparty;

use VanillePlugin\inc\{
	TypeCheck, Arrayify, Stringify, Post
};

/**
 * Third-Party helper class.
 */
final class Helper
{
	/**
	 * @inheritdoc
	 */
	public static function hasCache() : bool
	{
		return defined('WP_CACHE') && (WP_CACHE == true);
	}

    /**
	 * @inheritdoc
	 */
	public static function isArray($value) : bool
    {
        return TypeCheck::isArray($value);
    }

	/**
	 * @inheritdoc
	 */
	public static function isClass(string $class, bool $autoload = true) : bool
	{
		return TypeCheck::isClass($class, $autoload);
	}

	/**
	 * @inheritdoc
	 */
	public static function isSubClassOf(string $sub, string $class) : bool
	{
		return TypeCheck::isSubClassOf($sub, $class);
	}

	/**
	 * @inheritdoc
	 */
	public static function hasMethod($object, string $method) : bool
	{
		return TypeCheck::hasMethod($object, $method);
	}

	/**
	 * @inheritdoc
	 */
	public static function isFunction(string $function) : bool
	{
		return TypeCheck::isFunction($function);
	}

	/**
	 * @inheritdoc
	 */
	public static function keys(array $array) : array
	{
		return Arrayify::keys($array);
	}

	/**
	 * @inheritdoc
	 */
	public static function undash(string $string, bool $isGlobal = false) : string
	{
		return Stringify::undash($string, $isGlobal);
	}

	/**
	 * @inheritdoc
	 */
	public static function sanitizeText(string $string) : string
	{
		return Stringify::sanitizeText($string);
	}

	/**
	 * @inheritdoc
	 */
	public static function getRefererId() : string
	{
		return Post::getRefererId();
	}

	/**
	 * @inheritdoc
	 */
	public static function isAdmin() : bool
	{
		return is_admin();
	}

	/**
	 * @inheritdoc
	 */
	public static function addAction($hook, $callback, $priority = 10, $args = 1)
	{
		return add_action($hook, $callback, $priority, $args);
	}

	/**
	 * @inheritdoc
	 */
	public static function removeAction($hook, $callback, $priority = 10)
	{
		return remove_action($hook, $callback, $priority);
	}

	/**
	 * @inheritdoc
	 */
	public static function addFilter($hook, $callback, $priority = 10, $args = 1)
	{
		return add_filter($hook, $callback, $priority, $args);
	}

	/**
	 * @inheritdoc
	 */
	public static function hasFilter($hook, $callback = false)
	{
		return has_filter($hook, $callback);
	}

	/**
	 * @inheritdoc
	 */
	public static function removeFilter($hook, $callback, $priority = 10)
	{
		return remove_filter($hook, $callback, $priority);
	}

	/**
	 * @inheritdoc
	 */
	public static function applyFilter($hook, $value, ...$args)
	{
		return apply_filters($hook, $value, ...$args);
	}
}
