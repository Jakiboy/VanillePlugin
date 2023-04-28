<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.6
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\lib;

use VanillePlugin\int\ViewInterface;
use VanillePlugin\inc\Template;
use VanillePlugin\inc\Json;
use VanillePlugin\inc\Stringify;
use VanillePlugin\inc\File;
use VanillePlugin\inc\Session;
use VanillePlugin\inc\Exception as ErrorHandler;
use \Exception;

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
     * @param string $template
     * @return void
     */
    public function render($content = [], $template = 'default')
    {
        echo $this->assign($content,$template);
    }

	/**
	 * Aassign content to view.
	 *
     * @access public
	 * @param array $content
     * @param string $template
	 * @return mixed
	 */
	public function assign($content = [], $template = 'default')
	{
        // Set View environment
        $env = Template::getEnvironment($this->getPath($template),[
            'cache' => $this->getCachePath(),
            'debug' => $this->isDebug()
        ]);

        // Set custom callables
        if ($this->callables) {
            foreach ($this->callables as $name => $callable) {
                $env->addFunction(Template::extend($name,$callable));
            }
        }
    
		// Add view global functions
        $env->addFunction(Template::extend('dump', function($var) {
            var_dump($var);
        }));
        $env->addFunction(Template::extend('exit', function($status = null) {
            exit($status);
        }));
        $env->addFunction(Template::extend('getSession', function($var = null) {
            return Session::get($var);
        }));
        $env->addFunction(Template::extend('settingsFields', function($group) {
            settings_fields($group);
        }));
        $env->addFunction(Template::extend('settingsSections', function($group) {
            do_settings_sections($group);
        }));
        $env->addFunction(Template::extend('submitButton', function() {
            submit_button();
        }));
        $env->addFunction(Template::extend('isLoggedIn', function() {
            return $this->isLoggedIn();
        }));
        $env->addFunction(Template::extend('isDebug', function($global = false) {
            return $this->isDebug($global);
        }));
        $env->addFunction(Template::extend('getConfig', function($config) {
            return $this->getConfig($config);
        }));
        $env->addFunction(Template::extend('getPluginOption', function($o, $t = 'array', $d = false, $l = null) {
            return $this->getPluginOption($o,$t,$d,$l);
        }));
        $env->addFunction(Template::extend('getOption', function($option) {
            return $this->getOption($option);
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
            return $this->translateString($string);
        }));
        $env->addFunction(Template::extend('decodeJSON', function($json = '') {
            return Json::decode($json);
        }));
        $env->addFunction(Template::extend('encodeJSON', function($array = []) {
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
        $env->addFunction(Template::extend('applyFilter', function($hook, $value) {
            return $this->applyPluginFilter($hook,$value);
        }));
        $env->addFunction(Template::extend('doAction', function($hook, $args = null) {
            $this->doPluginAction($hook,$args);
        }));

        // Return rendered view
        try {
            $view = $env->load("{$template}{$this->getViewExtension()}");
            return $view->render($content);
        } catch (Exception $e) {
            ErrorHandler::clearLastError();
        }

        return false;
	}

    /**
     * Get view path (Overridden).
     *
     * @access protected
     * @param string $template
     * @return string
     */
    protected function getPath($template = '')
    {
        // Set overriding path
        $override = "{$this->getThemeDir()}/{$this->getNameSpace()}/";
        $override = $this->applyFilter(
            "{$this->getNameSpace()}-override-template-path",
            $override
        );
        if ( File::exists("{$override}{$template}{$this->getViewExtension()}") ) {
            return $override;
        }
        return $this->getViewPath();
    }
}
