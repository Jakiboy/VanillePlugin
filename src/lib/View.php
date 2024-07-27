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

namespace VanillePlugin\lib;

use VanillePlugin\int\ViewInterface;
use VanillePlugin\inc\{
    Template, Json, Stringify,
    File, Session, Exception as Handler
};

class View extends PluginOptions implements ViewInterface
{
    /**
     * @access private
     * @var array $callables
     */
    private $callables = false;

	/**
	 * Define custom callables.
	 *
	 * @access public
     * @param array $callables
	 * @return void
	 */
	public function setCallables($callables = [])
	{
		$this->callables = $callables;
	}

    /**
     * Render view.
     *
     * @access public
     * @param array $content
     * @param string $tpl
     * @return void
     */
    public function render($content = [], $tpl = 'default')
    {
        echo $this->assign($content,$tpl);
    }

	/**
	 * Aassign content to view.
	 *
     * @access public
	 * @param array $content
     * @param string $tpl
	 * @return mixed
	 */
	public function assign($content = [], $tpl = 'default')
	{
        // Set View environment
        $env = Template::getEnvironment($this->getPath($tpl),[
            'cache' => $this->getCachePath(),
            'debug' => $this->isDebug()
        ]);

        // Set custom callables
        if ( $this->callables ) {
            foreach ($this->callables as $name => $callable) {
                $env->addFunction(Template::extend($name, $callable));
            }
        }
    
		// Add view global functions
        if ( $this->isAdmin()) {

            $env->addFunction(Template::extend('settingsFields', function($group) {
                settings_fields($group);
            }));
            $env->addFunction(Template::extend('settingsSections', function($group) {
                do_settings_sections($group);
            }));
            $env->addFunction(Template::extend('submitButton', function() {
                submit_button();
            }));

        } else {

            $env->addFunction(Template::extend('exit', function($status = null) {
                exit($status);
            }));
            $env->addFunction(Template::extend('getSession', function($var = null) {
                return Session::get($var);
            }));

        }

        $env->addFunction(Template::extend('dump', function($var) {
            var_dump($var);
        }));
        $env->addFunction(Template::extend('isDebug', function($global = true) {
            return $this->isDebug($global);
        }));
        $env->addFunction(Template::extend('isLoggedIn', function() {
            return $this->isLoggedIn();
        }));
        $env->addFunction(Template::extend('getConfig', function($config) {
            return $this->getConfig($config);
        }));
        $env->addFunction(Template::extend('getPluginOption', function(string $k, $d = [], bool $l = false) {
            return $this->getPluginOption($k, $d, $l);
        }));
        $env->addFunction(Template::extend('getOption', function($k, $d = false) {
            return $this->getOption($k, $d);
        }));
        $env->addFunction(Template::extend('getRoot', function() {
            return $this->getRoot();
        }));
        $env->addFunction(Template::extend('getBaseUrl', function() {
            return $this->getBaseUrl();
        }));
        $env->addFunction(Template::extend('getAssetUrl', function() {
            return $this->getAssetUrl();
        }));
        $env->addFunction(Template::extend('nonce', function($action = -1) {
            return $this->createNonce($action);
        }));
        $env->addFunction(Template::extend('translate', function($string) {
            return $this->translate($string);
        }));
        $env->addFunction(Template::extend('translateVar', function(string $string, $vars = null) {
            return $this->translateVar($string, $vars);
        }));
        $env->addFunction(Template::extend('toJson', function($json = '') {
            return Json::decode($json);
        }));
        $env->addFunction(Template::extend('unJson', function($array = []) {
            return Json::encode($array);
        }));
        $env->addFunction(Template::extend('serialize', function($data) {
            return Stringify::serialize($data);
        }));
        $env->addFunction(Template::extend('unserialize', function($string) {
            return Stringify::unserialize($string);
        }));
        $env->addFunction(Template::extend('hasFilter', function($hook) {
            return $this->hasPluginFilter($hook);
        }));
        $env->addFunction(Template::extend('applyFilter', function($hook, ...$args) {
            return $this->applyPluginFilter($hook, ...$args);
        }));
        $env->addFunction(Template::extend('doAction', function($hook, ...$args) {
            $this->doPluginAction($hook, ...$args);
        }));

        // Return rendered view
        try {
            $view = $env->load("{$tpl}{$this->getViewExtension()}");
            return $view->render($content);
        } catch (\Exception $e) {
            if ( $this->isDebug() ) {
                die($e);
            }
            Handler::clearLastError();
        }

        return false;
	}

    /**
     * Get view path (Overridden).
     *
     * @access protected
     * @param string $tpl
     * @return string
     */
    protected function getPath($tpl = '')
    {
        $path = $this->getThemeDir($this->getNameSpace());
        $path = $this->applyPluginFilter('template-path', $path);
        $file = "{$path}{$tpl}{$this->getViewExtension()}";
        if ( File::isFile($file) ) {
            return $path;
        }
        return $this->getViewPath();
    }
}
