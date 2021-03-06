<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.6.8
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

final class Response extends Status
{
	/**
	 * @param string $message
	 * @param array $content
	 * @param string $status
	 * @param int $code 200
	 * @return string
	 */
	public static function set($message = '', $content = [], $status = 'success', $code = 200)
	{
		self::setHttpHeaders($code);
		echo Json::encode([
			'status'  => $status,
			'message' => $message,
			'content' => $content
		]);
		die();
	}

	/**
	 * @access public 
	 * @param int $code
	 * @param string $type
	 * @return void
	 */
	public static function setHttpHeaders($code, $type = 'application/json')
	{
		$status = self::getMessage($code);
		$protocol = Server::get('SERVER_PROTOCOL');
		header("Content-Type: {$type}");
		header("{$protocol} {$code} {$status}");
	}

	/**
	 * @param string $reponse
	 * @param bool $isArray false
	 * @return mixed
	 */
	public static function get($reponse, $isArray = false)
	{
		return Json::decode($reponse,$isArray);
	}
}
