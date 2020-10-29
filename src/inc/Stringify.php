<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.3.2
 * @copyright : (c) 2018 - 2020 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework
 */

namespace VanillePlugin\inc;

final class Stringify
{
	/**
	 * @access public
	 * @param array $search
	 * @param array $replace
	 * @param string $subject
	 * @return string
	 */
	public static function replace($search = [], $replace = [], $subject)
	{
		return str_replace($search,$replace,$subject);
	}

	/**
	 * @access public
	 * @param array $search
	 * @param array $replace
	 * @return string
	 */
	public static function replaceArray($replace = [], $subject)
	{
		if ( is_array($replace) ) {
			foreach ($replace as $key => $value) {
				$subject = self::replace($key,$value,$subject);
			}
		}
		return $subject;
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function lowercase($string)
	{
		return strtolower($string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function uppercase($string)
	{
		return strtoupper($string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function capitalize($string)
	{
		return ucfirst(self::lowercase($string));
	}
	/**
	 * @since 4.0.0
	 * @version 5.5.1
	 * @access public
	 * @param array $array
	 * @return object
	 */
	public static function toObject($array = [])
	{
	    if ( empty($array) || !is_array($array) ) {
	    	return false;
	    }
	    $obj = new \stdClass;
	    foreach ( $array as $key => $val ) {
	        $obj->{$key} = $val;
	    }
	    return $obj;
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function slugify($string)
	{
	  	// Replace non letter or digits by -
	  	$slug = preg_replace('~[^\pL\d]+~u', '-', $string);
	  	// Transliterate
		$json = new Json(dirname(__FILE__).'/bin/accents.json');
		$accents = $json->parse(true);
	  	$slug = strtr($slug, $accents);
	  	$slug = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $slug);
	  	// Temove unwanted characters
	  	$slug = preg_replace('~[^-\w]+~', '', $slug);
	  	// trim
	  	$slug = trim($slug, '-');
	  	// remove duplicate -
	  	$slug = preg_replace('~-+~', '-', $slug);
	  	// lowercase
	  	$slug = strtolower($slug);
	  	return !empty($slug) ? $slug : sanitize_title($string);
	}

	/**
	 * Search string
	 *
	 * @access public
	 * @param string $string
	 * @param string $search
	 * @return boolean
	 */
	public static function contains($string, $search)
	{
		if ( strpos($string, $search) !== false ) {
		    return true;
		}
		return false;
	}

	/**
	 * Format Path
	 *
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function formatPath($path)
	{
		return wp_normalize_path($path);
	}

	/**
	 * @access public
	 * @param string $key
	 * @return string
	 */
	public static function formatKey($key)
	{
	    $chars = [
	        "{"  => "rcb",
	        "}"  => "lcb",
	        "("  => "rpn",
	        ")"  => "lpn",
	        "/"  => "fsl",
	        "\\" => "bsl",
	        "@"  => "ats",
	        ":"  => "cln"
	    ];
	    foreach ($chars as $character => $replacement) {
	        if (strpos($key, $character)) {
	            $key = self::replace($character, "~{$replacement}~", $key);
	        }
	    }
	    return $key;
	}

	/**
	 * Deeply strip slashes
	 *
	 * @see /Function_Reference/stripslashes_deep/
	 * @since 4.0.0
	 * @version 5.5.1
	 * @access public
	 * @param mixed $data
	 * @return mixed
	 */
	public static function slashStrip($data)
	{
		$data = stripslashes_deep($data);
	    return $data;
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function numberStrip($string)
	{
		return preg_replace('/[0-9]+/', '', $string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function charStrip($string)
	{
		return preg_replace('/[^a-zA-Z0-9\s]/', '', $string);
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function spaceStrip($string)
	{
		return preg_replace('/\s+/', '', trim($string));
	}

	/**
	 * @access public
	 * @param string $string
	 * @return string
	 */
	public static function breakStrip($string)
	{
		return normalize_whitespace($string);
	}

	/**
	 * Unserialize value only if it was serialized.
	 *
	 * @see /Function_Reference/maybe_serialize/
	 * @since 4.0.0
	 * @version 5.5.1
	 * @access public
	 * @param string $string
	 * @return mixed
	 */
	public static function unserialize($string)
	{
		return maybe_unserialize($string);
	}

	/**
	 * Serialize data, if needed.
	 *
	 * @see /Function_Reference/maybe_serialize/
	 * @since 4.0.0
	 * @version 5.5.1
	 * @access public
	 * @param mixed $data
	 * @return mixed
	 */
	public static function serialize($data)
	{
		return maybe_serialize($data);
	}

	/**
	 * Get an excerpt of text
	 *
	 * @param string $content, int $length, string $more
	 * @return string
	 */
	public static function excerpt($content, $length = 40, $more = '[...]')
	{
		$excerpt = strip_tags( trim( $content ) );
		$words = str_word_count( $excerpt, 2 );
		if ( count( $words ) > $length ) {
			$words = array_slice( $words, 0, $length, true );
			end( $words );
			$position = key( $words ) + strlen( current( $words ) );
			$excerpt = substr( $excerpt, 0, $position ) . ' ' . $more;
		}
		return $excerpt;
	}

	/**
	 * @param mixed $number
	 * @param int $decimals
	 * @param string $dSep Decimals Separator
	 * @param string $tSep Thousands Separator
	 * @return float
	 */
	public static function toMoney($number, $decimals = 2, $dSep = '.', $tSep = ' ')
	{
		return number_format((float)$number,$decimals,$dSep,$tSep);
	}
}
