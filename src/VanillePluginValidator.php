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

namespace VanillePlugin;

use VanillePlugin\inc\{
	Validator, TypeCheck, File, GlobalConst
};
use VanillePlugin\exc\{
	NamepsaceException, ConfigException
};
use JsonSchema\Validator as JsonValidator;

final class VanillePluginValidator extends Validator
{
	/**
	 * @access public
	 * @var mixed $plugin
	 * @return void
	 * @throws NamepsaceException
	 */
	public static function checkNamespace($plugin)
	{
		if ( !self::isValidNamespace($plugin) ) {
			$namespace = $plugin->getNameSpace();
			if ( TypeCheck::isObject($namespace) ) {
				$namespace = '{Object}';
			} elseif ( TypeCheck::isArray($namespace) ) {
				$namespace = '[Array]';
			} else {
				$namespace = (string)$namespace;
			}
	        throw new NamepsaceException(
	            NamepsaceException::invalidPluginNamepsace($namespace)
	        );
		}
	}

	/**
	 * @access public
	 * @var object $global,
	 * @var string $file
	 * @return void
	 * @throws ConfigException
	 */
	public static function checkConfig($global, $file = null)
	{
		$error = self::isValidConfig($global);
		if ( TypeCheck::isString($error) ) {
	        throw new ConfigException(
	            ConfigException::invalidConfig($error,$file)
	        );
		} elseif ( $error === false ) {
	        throw new ConfigException(
	            ConfigException::invalidConfigFormat($file)
	        );
		}
	}

	/**
	 * Validate plugin namespace.
	 * 
	 * @access private
	 * @var object $plugin
	 * @return bool
	 */
	private static function isValidNamespace($plugin)
	{
		if ( TypeCheck::isObject($plugin) ) {
			if ( TypeCheck::isString($plugin->getNameSpace()) ) {
				if ( !empty($namespace = $plugin->getNameSpace()) ) {
					return File::exists(
						GlobalConst::pluginDir("/{$namespace}/{$namespace}.php")
					);
				}
			}
		}
		return false;
	}

	/**
	 * Validate plugin configuration.
	 * 
	 * @access private
	 * @var object $config
	 * @return mixed
	 */
	private static function isValidConfig($config)
	{
		if ( TypeCheck::isObject($config) ) {
			$validator = new JsonValidator;
			$validator->validate($config, (object)[
				'$ref' => 'file://' . dirname(__FILE__).'/config.schema.json'
			]);
			if ( $validator->isValid() ) {
				return true;
			} else {
				$errors = [];
			    foreach ($validator->getErrors() as $error) {
			        $errors[] = sprintf("[%s] %s",$error['property'],$error['message']);
			    }
			    return implode("\n", $errors);
			}
		}
		return false;
	}
}
