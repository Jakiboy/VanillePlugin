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

namespace VanillePlugin\inc;

final class Attachment
{
	/**
	 * Insert attachment,
	 * Returns attachment data if success.
	 *
	 * @access public
	 * @param string $path
	 * @param array $data
	 * @return mixed
	 */
	public static function insert($path, $data)
	{
		if ( ($id = self::preInsert($path,$data)) ) {
			self::update($id); // Should not be tested
			return [
				'id'  => $id,
				'url' => $data['url']
			];
		}
		return false;
	}

	/**
	 * Pre-insert attachment,
	 * Returns attachment Id on success.
	 *
	 * @access public
	 * @param string $path
	 * @param array $data
	 * @return int
	 */
	public static function preInsert($path, $data = [])
	{
		$data = self::getDefaultData($data);
		if ( empty($data['title']) ) {
			$data['title'] = File::getName($path);
		}
		$attachment = [
	        'guid'           => $data['url'],
	        'post_title'     => Stringify::sanitizeText($data['title']),
	        'post_content'   => Stringify::sanitizeText($data['content']),
	        'post_excerpt'   => Stringify::sanitizeText($data['excerpt']),
	        'post_mime_type' => $data['type'],
	        'post_status'    => $data['status']
		];
		return (int)wp_insert_attachment($attachment,$path,$data['parent']);
	}

	/**
	 * Update attachment by Id.
	 *
	 * @access public
	 * @param int $id
	 * @return bool
	 */
	public static function update($id)
	{
		$post = Post::get($id);
		$path = self::getAttachedFile($post->ID);
		$meta = self::generateMeta($id,$path);
		return (bool)self::updateMeta($id,$meta);
	}

	/**
	 * Get attached file path by Id.
	 *
	 * @access public
	 * @param int $id
	 * @return string
	 */
	public static function getAttachedFile($id)
	{
		return (string)get_attached_file($id);
	}

	/**
	 * Generate attachment meta data by Id.
	 *
	 * @access public
	 * @param int $id
	 * @param string $path
	 * @return array
	 */
	public static function generateMeta($id,$path)
	{
		if ( !TypeCheck::isFunction('wp_generate_attachment_metadata') ) {
		    require_once(GlobalConst::rootDir('wp-admin/includes/image.php'));
		}
		return (array)wp_generate_attachment_metadata($id,$path);
	}

	/**
	 * Update attachment meta data by Id.
	 *
	 * @access public
	 * @param int $id
	 * @param array $data
	 * @return bool
	 */
	public static function updateMeta($id,$data)
	{
		return (bool)wp_update_attachment_metadata($id,$data);
	}

	/**
	 * Get attachment Id by title.
	 *
	 * @access public
	 * @param string $title
	 * @return int
	 */
	public static function getIdByTitle($title)
	{
		return self::getIdByUrl(
			self::getUrlByTitle($title)
		);
	}

	/**
	 * Get attachment url by Id.
	 *
	 * @access public
	 * @param int $id
	 * @return string
	 */
	public static function getUrlById($id)
	{
		return wp_get_attachment_url($id);
	}

	/**
	 * Get attachment Id by url.
	 *
	 * @access public
	 * @param string $url
	 * @return int
	 */
	public static function getIdByUrl($url)
	{
		return attachment_url_to_postid($url);
	}

	/**
	 * Get attachment url by title.
	 *
	 * @access public
	 * @param string $title
	 * @return mixed
	 */
	public static function getUrlByTitle($title)
	{
		if ( ($attachment = Post::getByTitle($title, 'attachment')) ) {
			return $attachment['guid'] ?? false;
		}
		return false;
	}

	/**
	 * Get attachment image source by id.
	 *
	 * @access public
	 * @param int $id
	 * @param string $size
	 * @return string
	 */
	public static function getImageById($id, $size = 'thumbnail')
	{
		$src = wp_get_attachment_image_src($id,$size);
		return $src[0] ?? '';
	}

	/**
	 * Get default attachment data.
	 *
	 * @access public
	 * @param array $data
	 * @return array
	 */
	public static function getDefaultData($data = [])
	{
		return Arrayify::merge([
			'url'     => '',
			'title'   => '',
			'content' => '',
			'excerpt' => '',
			'type'    => '',
			'status'  => 'inherit',
			'parent'  => 0
		],$data);
	}
}
