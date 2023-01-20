<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.4
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

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
			'code'    => $code,
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
		$protocol = Server::get('server-protocol');
		header("Content-Type: {$type}");
		header("{$protocol} {$code} {$status}");
	}
}
