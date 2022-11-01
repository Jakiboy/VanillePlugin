<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.1
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\int;

interface RequestInterface
{
    /**
     * @param string $method
     * @param string $baseUrl
     */
    function __construct($method = 'GET', $params = [], $baseUrl = null);

    /**
     * Set additional request args.
     * 
     * @param array $args
     * @return object
     */
    function setArgs($args = []);

    /**
     * @param string $arg
     * @param mixed $value
     * @return void
     */
    function addArg($arg,$value);

    /**
     * @param array $headers
     * @return object
     */
    function setHeaders($headers = []);

    /**
     * @param array $cookies
     * @return object
     */
    function setCookies($cookies = []);

    /**
     * @param array $body
     * @return object
     */
    function setBody($body = []);

    /**
     * @param string $method
     * @return object
     */
    function setMethod($method);

    /**
     * @param string $url
     * @return object
     */
    function setBaseUrl($url);

    /**
     * @param void
     * @return object
     */
    function send($url = null);

    /**
     * @param string $url
     * @param array $args
     * @return object
     */
    function post($url, $args = []);

    /**
     * @param string $url
     * @param array $args
     * @return object
     */
    function get($url, $args = []);

    /**
     * @param string $url
     * @param array $args
     * @return object
     */
    function head($url, $args = []);

    /**
     * @param void
     * @return int
     */
    function getStatusCode();

    /**
     * @param void
     * @return string
     */
    function getBody();
    
    /**
     * @param void
     * @return string
     */
    function hasError();
}
