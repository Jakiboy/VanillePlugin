<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.5
 * @copyright : (c) 2018 - 2021 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\lib;

use VanillePlugin\int\PluginNameSpaceInterface;
use VanillePlugin\inc\Date;

final class Session extends PluginOptions
{
    /**
     * @param PluginNameSpaceInterface $plugin
     */
    public function __construct(PluginNameSpaceInterface $plugin)
    {
        // Init plugin config
        $this->initConfig($plugin);
        
        if ( !self::isSetted() ) {
            session_start();
        }
    }
    
    /**
     * Register the session
     *
     * @access public
     * @param int $time
     * @return void
     */
    public static function register($time = 60)
    {
        self::set('--session-id', session_id());
        self::set('--session-time', intval($time));
        self::set('--session-start', Date::newTime(0, 0, self::get('--session-time')));
    }

    /**
     * Check if session is registered
     *
     * @access public
     * @param void
     * @return bool
     */
    public static function isRegistered()
    {
        if ( !empty(self::get('--session-id')) ) {
            return true;
        }
        return false;
    }

    /**
     * Set key in session
     *
     * @access public
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public static function set($key, $value = null)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Retrieve value stored in session by key
     *
     * @access public
     * @param string $item
     * @return mixed
     */
    public static function get($item = null)
    {
        if ( $item ) {
            return self::isSetted($item) ? $_SESSION[$item] : false;
        }
        return isset($_SESSION) && !empty($_SESSION);
    }

    /**
     * Check key exists
     *
     * @access public
     * @param string $key
     * @return bool
     */
    public static function isSetted($key = null)
    {
        if ( $key ) {
            return isset($_SESSION[$key]);
        }
        return isset($_SESSION) && !empty($_SESSION);
    }

    /**
     * Retrieve global session variable
     *
     * @access public
     * @param void
     * @return array
     */
    public static function getSession()
    {
        return $_SESSION;
    }

    /**
     * Get id for current session
     *
     * @access public
     * @param void
     * @return int
     */
    public static function getSessionId()
    {
        return self::get('--session-id');
    }

    /**
     * Check if session is over
     *
     * @access public
     * @param void
     * @return bool
     */
    public static function isExpired()
    {
        if ( self::get('--session-start') < Date::timeNow() ) {
            return true;
        }
        return false;
    }

    /**
     * Renew session when the given time is not up
     *
     * @access public
     * @param void
     * @return void
     */
    public static function renew()
    {
        self::set('--session-start', Date::newTime(0, 0, self::get('--session-time')));
    }

    /**
     * Destroy session
     *
     * @access public
     * @param void
     * @return void
     */
    public static function end()
    {
        session_destroy();
        $_SESSION = [];
    }
}
