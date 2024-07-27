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

namespace VanillePlugin\inc;

final class TypeCheck
{
	/**
	 * @access public
	 * @param mixed $value
	 * @return bool
	 */
	public static function isString($value)
	{
		return is_string($value);
	}

	/**
	 * @access public
	 * @param mixed $value
	 * @param string $class
	 * @param bool $string, Allow string
	 * @return bool
	 */
	public static function isObject($value, $class = null, $string = false)
	{
		if ( $class ) {
			return is_a($value,$class,$string);
		}
		return is_object($value);
	}
	
	/**
	 * @access public
	 * @param mixed $value
	 * @return bool
	 */
	public static function isArray($value)
	{
		return is_array($value);
	}

	/**
	 * @access public
	 * @param mixed $value
	 * @param bool $string
	 * @return bool
	 */
	public static function isInt($value, $string = false)
	{
		if ( $string ) {
			return is_numeric($value);
		}
		return is_int($value);
	}

	/**
	 * @access public
	 * @param mixed $value
	 * @param bool $string
	 * @return bool
	 */
	public static function isFloat($value, $string = false)
	{
		if ( $string ) {
			$value = (float)$value;
		}
		return is_float($value);
	}

	/**
	 * @access public
	 * @param mixed $value
	 * @return bool
	 */
	public static function isBool($value)
	{
		return is_bool($value);
	}

	/**
	 * @access public
	 * @param mixed $value
	 * @return bool
	 */
	public static function isNull($value)
	{
		return is_null($value);
	}

	/**
	 * @access public
	 * @param mixed $value
	 * @return bool
	 */
	public static function isNan($value)
	{
		return is_nan($value);
	}

	/**
	 * @access public
	 * @param mixed $value
	 * @return bool
	 */
	public static function isCallable($value)
	{
		return is_callable($value);
	}

	/**
	 * @access public
	 * @param string $function
	 * @return bool
	 */
	public static function isFunction($function)
	{
		return function_exists($function);
	}

	/**
	 * @access public
	 * @param string $class
	 * @return bool
	 */
	public static function isClass($class)
	{
		return class_exists($class);
	}

	/**
	 * @access public
	 * @param string $sub
	 * @param string $class
	 * @return bool
	 */
	public static function isSubClassOf($sub, $class)
	{
		return is_subclass_of($sub,$class);
	}

	/**
	 * Check interface.
	 *
	 * @access public
	 * @param mixed $class
	 * @param string $interface
	 * @param bool $short
	 * @return bool
	 */
	public static function hasInterface($class, string $interface, bool $short = true) : bool
	{
		$implements = class_implements($class);
		if ( $short ) {
			foreach ($implements as $key => $value) {
				$implements[$key] = Stringify::basename($value);
			}
		}
		return Arrayify::inArray($interface, (array)$implements);
	}

	/**
	 * @access public
	 * @param object $object
	 * @param string $method
	 * @return bool
	 */
	public static function hasMethod($object, $method)
	{
		return method_exists($object,$method);
	}

	/**
	 * @access public
	 * @param mixed $value
	 * @return bool
	 */
	public static function isCountable($value)
	{
		return is_countable($value);
	}

	/**
	 * @access public
	 * @param mixed $value
	 * @return bool
	 */
	public static function isResource($value)
	{
		return is_resource($value);
	}

	/**
	 * @access public
	 * @param mixed $value
	 * @return bool
	 */
	public static function isScalar($value)
	{
		return is_scalar($value);
	}

	/**
	 * @access public
	 * @param string $path
	 * @return bool
	 */
	public static function isStream($path)
	{
	    $scheme = strpos($path,'://');
	    if ( false === $scheme ) {
	        return false;
	    }
	    $stream = substr($path,0,$scheme);
	    return Arrayify::inArray($stream,stream_get_wrappers(),true);
	}
}
