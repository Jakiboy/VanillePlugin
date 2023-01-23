<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.5
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

final class ResponseXML
{
	/**
	 * @access public 
	 * @param string $xml
	 * @return string
	 */
	public static function format($xml)
	{
		$xml = Stringify::replace('<?xml version="1.0" encoding="utf-8" ?>','',(string)$xml);
		$xml = Stringify::replace('</xml>','',$xml);
		return $xml;
	}
	
	/**
	 * Parse XML string.
	 * 
	 * LIBXML_NOCDATA: 16384
	 * LIBXML_VERSION: 20908
	 * 
	 * @access public 
	 * @param string $xml
	 * @param int $args
	 * @return mixed
	 */
	public static function parse($xml, $args = 16384|20908)
	{
		return @simplexml_load_string($xml,'SimpleXMLElement',$args);
	}

	/**
	 * Parse XML file.
	 * 
	 * @access public 
	 * @param string $xml
	 * @param int $args
	 * @return mixed
	 */
	public static function parseFile($xml, $args = 16384|20908)
	{
		return @simplexml_load_file($xml,'SimpleXMLElement',$args);
	}

	/**
	 * Ignore XML errors.
	 * 
	 * @access public 
	 * @param bool $user, User errors
	 * @return mixed
	 */
	public static function ignoreErrors($user = true)
	{
		return libxml_use_internal_errors($user);
	}
}
