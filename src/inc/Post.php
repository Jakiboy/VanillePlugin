<?php
/**
 * @author    : Jakiboy
 * @package   : VanillePlugin
 * @version   : 1.1.x
 * @copyright : (c) 2018 - 2024 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

class Post
{
	/**
	 * Get post by Id.
	 *
	 * @access public
	 * @param mixed $id
	 * @param bool $format
	 * @return mixed
	 */
	public static function get($id = null, bool $format = true)
	{
		if ( $id ) {
			return self::getById($id, $format);
		}
		return self::current($format);
	}

	/**
	 * Get current post Id.
	 *
	 * @access public
	 * @return int
	 */
	public static function getId() : int
	{
		global $post;
		return $post->ID ?? 0;
	}

	/**
	 * Get current post.
	 *
	 * @access public
     * @param bool $format
	 * @return mixed
	 */
	public static function current(bool $format = true)
	{
		global $post;
        if ( $format ) {
            return Format::post($post);
        }
        return $post;
	}

	/**
	 * Get post referer Id.
	 *
	 * @access public
	 * @return mixed
	 */
	public static function getRefererId()
	{
		if ( ($url = Server::getReferer()) ) {
			return self::getUrlId($url);
		}
		return false;
	}

	/**
	 * Get post URL Id.
	 *
	 * @access public
	 * @param string $url
	 * @return mixed
	 */
	public static function getUrlId(string $url)
	{
		return url_to_postid($url);
	}

	/**
     * Get post by Id.
     * 
	 * @access public
	 * @param mixed $id
	 * @param bool $format
	 * @return mixed
	 */
	public static function getById($id = null, bool $format = true)
	{
		if ( !$id ) $id = self::getId();
		$post = get_post($id);
        if ( $format ) {
            return Format::post($post);
        }
		return $post;
	}

	/**
	 * Get post title by Id.
	 *
	 * @access public
	 * @param mixed $id
	 * @param string $default
	 * @return string
	 */
	public static function getTitle($id = null, string $default = 'Archive') : string
	{
		if ( !$id ) $id = self::getId();
		$title = get_the_title($id);
		return !empty($title) ? $title : $default;
	}
	
	/**
	 * Get loop post content.
	 *
	 * @access public
	 * @return mixed
	 */
	public static function getContent()
	{
		return get_the_content();
	}

	/**
	 * Get post URL by Id.
	 *
	 * @access public
	 * @param mixed $id
	 * @return string
	 */
	public static function getUrl($id = null) : string
	{
		if ( !$id ) $id = self::getId();
		return (string)get_permalink($id);
	}

	/**
	 * Get all post types.
	 *
	 * @access public
	 * @param mixed $args
	 * @param string $output
	 * @param string $operator
	 * @return array
	 */
	public static function getTypes($args = [], string $output = 'names', string $operator = 'and') : array
	{
		return get_post_types($args, $output, $operator);
	}

	/**
	 * Get post by title.
	 *
	 * @access public
	 * @param string $title
	 * @param string $operator
	 * @return array
	 */
	public static function getByTitle(string $title, string $type = 'post') : array
	{
		$posts = [];
		$query = new Query([
			'name'      => $title,
			'post-type' => $type
		]);
		if ( $query->havePosts() ) {
			$posts = $query->posts;
		}
		return $posts;
	}

	/**
     * Get post thumbnail.
     *
	 * @access public
	 * @param mixed $id
	 * @return mixed
	 */
	public static function getThumbnail($id = null, $size = 'post-thumbnail')
    {
        return get_the_post_thumbnail_url($id, $size);
    }

	/**
	 * Add post.
	 *
	 * @access public
	 * @param mixed $data
	 * @param bool $error
	 * @param bool $after
	 * @return mixed
	 */
	public static function add($data, bool $error = false, bool $after = true)
	{
		$data = Stringify::underscore($data);
		return wp_insert_post($data, $error, $after);
	}

	/**
	 * Update post.
	 *
	 * @access public
	 * @param mixed $data
	 * @param bool $error
	 * @param bool $after
	 * @return mixed
	 */
	public static function update($data, bool $error = false, bool $after = true)
	{
		return wp_update_post($data, $error, $after);
	}

	/**
	 * Delete post.
	 *
	 * @access public
	 * @param mixed $id
	 * @param bool $force
	 * @return bool
	 */
	public static function delete($id = null, bool $force = false) : bool
	{
		if ( !$id ) $id = self::getId();
		return (bool)wp_delete_post((int)$id, $force);
	}

	/**
     * Register post type.
     *
	 * @access public
	 * @param string $type
	 * @param array $args
	 * @return mixed
	 */
	public static function registerType(string $type, array $args = [])
	{
		return register_post_type($type, $args);
	}

	/**
     * Get meta.
     *
	 * @access public
	 * @param string $key
	 * @param mixed $id
	 * @param bool $single
	 * @return mixed
	 */
	public static function getMeta(string $key, $id = null, bool $single = true)
	{
		if ( !$id ) $id = self::getId();
		return get_post_meta((int)$id, $key, $single);
	}

	/**
     * Update meta.
     * 
	 * @access public
	 * @param string $key
	 * @param mixed $value
	 * @param mixed $id
	 * @return mixed
	 */
	public static function updateMeta(string $key, $value, $id = null)
	{
		if ( !$id ) $id = self::getId();
		return update_post_meta((int)$id, $key, $value);
	}

	/**
     * Delete meta.
     * 
	 * @access public
	 * @param string $key
	 * @param mixed $id
	 * @param mixed $value
	 * @return bool
	 */
	public static function deleteMeta(string $key, $id = null, $value = null) : bool
	{
		if ( !$id ) $id = self::getId();
		return delete_post_meta((int)$id, $key, $value);
	}

	/**
	 * Get all posts.
	 *
	 * @access public
	 * @param array $args
	 * @param bool $format
	 * @return array
	 */
	public static function all(array $args = [], bool $format = true) : array
	{
		$args = Arrayify::merge([
			'post-type'      => 'any',
			'posts-per-page' => -1
		], $args);
		$args = Stringify::underscore($args);

		$posts = get_posts($args);
        if ( $format ) {
			$posts = Arrayify::map(function($post) {
				return Format::post($post);
			}, $posts);
        }
        return $posts;
	}
}
