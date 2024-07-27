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

use VanillePlugin\int\ShortcodeInterface;
use VanillePlugin\inc\Shortcode;

/**
 * Wrapper class for shortcode.
 */
abstract class AbstractShortcode extends View implements ShortcodeInterface
{
	/**
	 * Do shortcode main callable.
	 * 
	 * @access public
	 * @param array $atts
	 * @param string $content
	 * @param string $tag
	 * @return string
	 */
	abstract public function doCallable($atts = [], $content = null, $tag = '');

	/**
	 * @access protected
	 * @param array $default
	 * @param array $atts
	 * @param string $tag
	 * @return array
	 */
	protected function attributes($default = [], $atts = [], $tag = '')
	{
		return Shortcode::attributes($default,$atts,$tag);
	}

	/**
	 * Format shortcode attributes.
	 * 
	 * @access protected
	 * @param array $atts
	 * @return array
	 */
	protected function formatAttributes($atts = [])
	{
		return Shortcode::formatAttributes($atts);
	}
}
