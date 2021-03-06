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

namespace VanillePlugin\lib;

use VanillePlugin\int\PluginNameSpaceInterface;

final class Session extends PluginOptions
{
	/**
	 * @param PluginNameSpaceInterface $plugin
	 */
	public function __construct(PluginNameSpaceInterface $plugin)
	{
		// Init plugin config
		$this->initConfig($plugin);
        if ( !$this->isSetted() ) {
            session_start();
        }
	}
    
    /**
     * Register the session
     *
     * @access public
     * @param int $time 60
     * @return void
     */
    public function register($time = 60)
    {
        $this->set('sessionId', session_id());
        $this->set('sessionTime', intval($time));
        $this->set('sessionStart', $this->newTime());
    }

    /**
     * Check if session is registered
     *
     * @access public
     * @param void
     * @return bool
     */
    public function isRegistered()
    {
        if ( !empty($this->get('sessionId')) ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Set key in session
     *
     * @access public
     * @param mixed $key
     * @param mixed $value
     * @return void
     */
    public function set($key, $value)
    {
        $_SESSION[$this->getNameSpace()][$key] = $value;
    }

    /**
     * Retrieve value stored in session by key
     *
     * @access public
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->isSetted($key) 
        ? $_SESSION[$this->getNameSpace()][$key] : false;
    }

    /**
     * Check key exists
     *
     * @access public
     * @param string $key null
     * @return bool
     */
    public function isSetted($key = null)
    {
        if ( $key ) {
            return isset($_SESSION[$this->getNameSpace()][$key]);
        } else {
            return isset($_SESSION);
        }
    }

    /**
     * Retrieve global session variable
     *
     * @access public
     * @param void
     * @return array
     */
    public function getSession()
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
    public function getSessionId()
    {
        return $this->get('sessionId');
    }

    /**
     * Check if session is over
     *
     * @access public
     * @param void
     * @return bool
     */
    public function isExpired()
    {
        if ( $this->get('sessionStart') < $this->timeNow() ) {
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
    public function renew()
    {
        $this->set('sessionStart', $this->newTime());
    }

    /**
     * Return current time
     *
     * @access public
     * @param void
     * @return unix timestamp
     */
    private function timeNow()
    {
        $currentHour = date('H');
        $currentMin  = date('i');
        $currentSec  = date('s');
        $currentMon  = date('m');
        $currentDay  = date('d');
        $currentYear = date('y');
        return mktime(
            $currentHour,
            $currentMin,
            $currentSec,
            $currentMon,
            $currentDay,
            $currentYear
        );
    }

    /**
     * Generates new time
     *
     * @access public
     * @param void
     * @return unix timestamp
     */
    private function newTime()
    {
        $currentHour = date('H');
        $currentMin  = date('i');
        $currentSec  = date('s');
        $currentMon  = date('m');
        $currentDay  = date('d');
        $currentYear = date('y');
        return mktime(
            $currentHour,
            ($currentMin + $this->get('sessionTime')),
            $currentSec,
            $currentMon,
            $currentDay,
            $currentYear
        );
    }

    /**
     * Destroy session
     *
     * @access public
     * @param void
     * @return void
     */
    public function end()
    {
        session_destroy();
        $_SESSION = [];
    }
}
