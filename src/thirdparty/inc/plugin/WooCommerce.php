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

namespace VanillePlugin\thirdparty\inc\plugin;

use VanillePlugin\thirdparty\Helper;

/**
 * WooCommerce plugin helper class.
 *
 * @see https://github.com/woocommerce/woocommerce
 */
final class WooCommerce
{
	/**
	 * Check whether plugin is active.
	 *
	 * @access public
	 * @return bool
	 */
	public static function isEnabled() : bool
	{
  		return Helper::isClass('\WooCommerce');
	}

	/**
	 * Get product by Id.
	 *
	 * @access public
	 * @param mixed $id
	 * @return mixed
	 */
	public static function getProduct($id = null)
	{
		if ( Helper::isFunction('wc_get_product') ) {
			return wc_get_product($id);
		}
		return false;
	}

	/**
	 * Add product options input.
	 * [Action: woocommerce_product_options_general_product_data].
	 *
	 * @access public
	 * @param mixed $field
	 * @return void
	 */
	public static function addInput($field = [])
	{
		if ( Helper::isFunction('woocommerce_wp_text_input') ) {
			woocommerce_wp_text_input($field);
		}
	}

	/**
	 * Add product options checkbox.
	 * [Action: woocommerce_product_options_general_product_data].
	 *
	 * @access public
	 * @param array $field
	 * @return array
	 */
	public static function addCheckbox($field = [])
	{
		if ( Helper::isFunction('woocommerce_wp_checkbox') ) {
			woocommerce_wp_checkbox($field);
		}
	}

	/**
	 * Add product options select.
	 * [Action: woocommerce_product_options_general_product_data].
	 *
	 * @access public
	 * @param array $field
	 * @return array
	 */
	public static function addSelect($field = [])
	{
		if ( Helper::isFunction('woocommerce_wp_select') ) {
			woocommerce_wp_select($field);
		}
	}
	
	/**
	 * Get product meta.
	 *
	 * @access public
	 * @param string $key
	 * @param mixed $product
	 * @return mixed
	 */
	public static function getMeta(string $key, $product = null)
	{
		if ( !Helper::isSubClassOf($product, '\WC_Data') ) {
			$product = self::getProduct($product);
		}
		return ($product) ? $product->get_meta($key) : false;
	}

	/**
	 * Update product meta.
	 * [Action: woocommerce_process_product_meta].
	 *
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @param mixed $product
	 * @return mixed
	 */
	public static function updateMeta(string $key, $value, $product = null)
	{
		if ( !Helper::isSubClassOf($product, '\WC_Data') ) {
			$product = self::getProduct($product);
		}
		if ( $product ) {
			$product->update_meta_data($key, Helper::sanitizeText($value));
			return $product->save();
		}
		return false;
	}

	/**
	 * Remove add to cart button.
	 * [Action: woocommerce_single_variation].
	 * [Callback: woocommerce_single_variation_add_to_cart_button].
	 *
	 * @access public
	 * @param mixed $priority
	 * @return bool
	 */
	public static function removeCartButton(int $priority = 20) : bool
	{
		$hook = 'woocommerce_single_variation';
		$callback = 'woocommerce_single_variation_add_to_cart_button';
		return Helper::removeAction($hook, $callback, $priority);
	}

	/**
	 * Disable price.
	 * [Action: woocommerce_get_price_html].
	 * [Callback: __return_false].
	 *
	 * @access public
	 * @param mixed $priority
	 * @return void
	 */
	public static function disablePrice(int $priority = 20)
	{
		$hook = 'woocommerce_get_price_html';
		$callback = '__return_false';
		Helper::addFilter($hook, $callback, $priority);
	}

	/**
	 * Disable stock.
	 * [Action: woocommerce_out_of_stock_message].
	 * [Callback: __return_false].
	 *
	 * @access public
	 * @param mixed $priority
	 * @return void
	 */
	public static function disableStock(int $priority = 20)
	{
		$hook = 'woocommerce_out_of_stock_message';
		$callback = '__return_false';
		Helper::addFilter($hook, $callback, $priority);
	}

	/**
	 * Disable purchase.
	 * [Action: woocommerce_out_of_stock_message].
	 * [Callback: __return_false].
	 *
	 * @access public
	 * @param mixed $priority
	 * @return void
	 */
	public static function disablePurchase(int $priority = 20)
	{
		$hook = 'woocommerce_out_of_stock_message';
		$callback = '__return_false';
		Helper::addFilter($hook, $callback, $priority);
	}

	/**
	 * @inheritdoc
	 */
	public static function addAction(string $hook, $callback, int $priority = 10, int $args = 1)
	{
		$hook = Helper::undash("woocommerce-{$hook}");
		Helper::addAction($hook, $callback, $priority, $args);
	}

	/**
	 * @inheritdoc
	 */
	public static function removeAction(string $hook, $callback, int $priority = 10)
	{
		$hook = Helper::undash("woocommerce-{$hook}");
		Helper::removeAction($hook, $callback, $priority);
	}
}
