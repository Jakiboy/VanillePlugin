<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.0.1
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\inc\{
	Request, Server
};

/**
 * Advanced request client helper.
 */
class API extends Request
{
    use \VanillePlugin\VanillePluginConfig,
		\VanillePlugin\tr\TraitSecurable;

	/**
	 * @access public
	 * @var string Server down code
	 */
	public const DOWN = 429;

	/**
	 * @access protected
	 * @var object $logger, API logger
	 * @var object $debug, Debug status
	 */
	protected $logger;
	protected $hasDebug = false;

	/**
	 * Init request client.
	 * 
	 * @inheritdoc
	 */
	public function __construct(string $method = 'GET', array $args = [], ?string $baseUrl = null)
	{
		// Init plugin config
		$this->initConfig();

		// Set args
		$this->method = $method;
		$this->baseUrl = $baseUrl;
		$this->args = $this->mergeArray([
			'timeout'     => 30,
			'redirection' => 0,
			'sslverify'   => true
		], Server::maybeRequireSSL($args));

		// Set logger
		$this->logger = new Logger();

		// Set debug status
		$this->hasDebug = $this->hasDebug();

        // Reset plugin config
        $this->resetConfig();
	}

	/**
	 * Send request and log response.
	 *
	 * @inheritdoc
	 */
	public function send(?string $url = null) : self
	{
		parent::send($url);
		if ( $this->hasError() && $this->hasDebug ) {
			$this->logger->debug($this->response, true);
		}
		return $this;
	}

	/**
	 * Set API authentication.
	 *
	 * @access public
	 * @param string $token
	 * @return void
	 */
	public function setAuthentication(string $token)
	{
		$this->addHeader(
			'Authorization',
			sprintf('Bearer %s', $token)
		);
	}

	/**
	 * Set API basic authentication.
	 * 
	 * @access public
	 * @param string $user
	 * @param string $pswd
	 * @return void
	 */
	public function setBasicAuthentication(string $user, string $pswd)
	{
		$token = $this->base64("{$user}:{$pswd}");
		$this->addHeader(
			'Authorization',
			sprintf('Basic %s', $token)
		);
	}

	/**
	 * Get API formated response from body (JSON).
	 * 
	 * @access public
	 * @param bool $isArray
	 * @return mixed
	 */
	public function getResponse(bool $isArray = true)
	{
		if ( !$this->hasError() ) {
			return $this->decodeJson($this->getBody(), $isArray);
		}
		return false;
	}

	/**
	 * Get API formated response from body (XML).
	 * 
	 * @access public
	 * @return mixed
	 */
	public function getXmlResponse()
	{
		if ( !$this->hasError() ) {
			return $this->parseXml($this->getBody());
		}
		return false;
	}

	/**
	 * Check whether API response has status (JSON).
	 * 
	 * @access public
	 * @param string $status
	 * @return bool
	 */
	public function hasStatus(string $status) : bool
	{
		if ( !$this->hasError() ) {
			$body = $this->decodeJson($this->getBody(), true);
			if ( isset($body['status']) && $body['status'] == $status ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check whether API response has content (JSON).
	 * 
	 * @access public
	 * @return bool
	 */
	public function hasContent() : bool
	{
		if ( !$this->hasError() ) {
			$body = $this->decodeJson($this->getBody(), true);
			return (isset($body['content']) && !empty($body['content']));
		}
		return false;
	}

	/**
	 * Check API response code,
	 * Down when response code lower than expected code (Default 429) or equals 0.
	 *
	 * @access public
	 * @param int $code
	 * @return bool
	 */
	public function isDown(int $code = self::DOWN) : bool
	{
		if ( !$this->getStatusCode() ) {
			return true;

		} elseif ( $this->getStatusCode() >= intval($code) ) {
			return true;
		}
		return false;
	}

	/**
	 * Force disabling SSL verification.
	 * 
	 * @access public
	 * @return void
	 */
	public function forceDisableSSL()
	{
		$this->args['sslverify'] = false;
	}
}
