<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.0.1
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

use VanillePlugin\int\ShortcodeInterface;
use VanillePlugin\lib\View;

abstract class AbstractShortcode extends View implements ShortcodeInterface
{
	/**
	 * @access public
	 * @param array $params
	 * @param string $content
	 * @param string $tag
	 * @return string
	 */
	abstract public function callable($atts = [], $content = null, $tag = '');

	/**
	 * @access protected
	 * @param array $default
	 * @param array $atts
	 * @param string $shortcode
	 * @return array
	 */
	protected function attributes($default = [], $atts = [], $shortcode = '')
	{
		return shortcode_atts($default, $atts, $shortcode);
	}
}