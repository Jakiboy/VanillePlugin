<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.1.6
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePluginTest\inc;

use VanillePluginTest\lib\PluginOptionsTest;
use VanillePluginTest\int\PluginNameSpaceInterfaceTest;

class LoggerTest extends PluginOptionsTest
{
    /**
     * @param PluginNameSpaceInterfaceTest $plugin
     * @return void
     */
    public function __construct(PluginNameSpaceInterfaceTest $plugin)
    {
        // Init plugin config
        $this->initConfig($plugin);
    }

    /**
     * @access public
     * @param string $message
     * @return void
     */
    public function debug($message)
    {
        $this->write('DEBUG', $message);
    }

    /**
     * @access public
     * @param string $message
     * @return void
     */
    public function error($message)
    {
        $this->write('ERROR', $message);
    }

    /**
     * @access public
     * @param string $message
     * @return void
     */
    public function warning($message)
    {
        $this->write('WARNING', $message);
    }

    /**
     * @access public
     * @param string $message
     * @return void
     */
    public function info($message)
    {
        $this->write('INFO', $message);
    }

    /**
     * @access private
     * @param string $status 
     * @param string $message 
     * @return void
     */
    private function write($status, $message)
    {
        $date = date('[d-m-Y]');
        $log = "{$this->getLoggerPath()}/debug-{$date}.log";
        $date = date('[d-m-Y H:i:s]');
        $msg = "{$date} : [{$status}] - {$message}" . PHP_EOL;
        File::w($log, $msg, true);
    }
}
