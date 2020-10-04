<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.4
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

final class ResponseXML
{
	/**
	 * @param string $xml
	 * @return string
	 */
	public static function format($xml)
	{
		$xml = str_replace('<?xml version="1.0" encoding="utf-8" ?>', '', $xml);
		$xml = str_replace('</xml>', '', $xml);
		return $xml;
	}

	/**
	 * @param string $xml
	 * @return mixed
	 */
	public static function parse($xml)
	{
		return simplexml_load_string($xml);
	}
}
