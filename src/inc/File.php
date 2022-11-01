<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.1
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

declare(strict_types=1);

namespace VanillePlugin\inc;

class File
{
	/**
	 * Analyse file.
	 *
	 * @access public
	 * @param string $path
	 * @return array
	 */
	public static function analyse($path)
	{
		return [
			'parent'      => self::getParentDir($path),
			'name'        => self::getName($path),
			'filename'    => self::getFileName($path),
			'extension'   => self::getExtension($path),
			'accessed'    => self::getLastAccess($path),
			'changed'     => self::getLastChange($path),
			'size'        => self::getSize($path),
			'permissions' => self::getPermissions($path)
		];
	}

	/**
	 * Get parent dir.
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public static function getParentDir($path)
	{
		return dirname(Stringify::formatPath($path));
	}

	/**
	 * Get file extension.
	 *
	 * @access public
	 * @param string $path
	 * @param bool $format
	 * @return string
	 */
	public static function getExtension($path, $format = true)
	{
		$ext = pathinfo(
			Stringify::formatPath($path),
			PATHINFO_EXTENSION
		);
		if ( $format ) {
			$ext = strtolower($ext);
		}
		return $ext;
	}

	/**
	 * Get file name.
	 *
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function getName($path)
	{
		$path = self::getFileName($path);
		$ext = self::getExtension($path);
		return Stringify::replace(".{$ext}",'',$path);
	}

	/**
	 * Get file full name.
	 *
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function getFileName($path)
	{
		return basename(Stringify::formatPath($path));
	}

	/**
	 * Get file last access.
	 *
	 * @access public
	 * @param string $path
	 * @return mixed
	 */
    public static function getLastAccess($path)
    {
    	$path = Stringify::formatPath($path);
        if ( self::exists($path) ) {
            if ( ($access = fileatime($path)) ) {
                return $access;
            }
        }
        return false;
    }

	/**
	 * Get file last change.
	 *
	 * @access public
	 * @param string $path
	 * @return mixed
	 */
    public static function getLastChange($path)
    {
    	$path = Stringify::formatPath($path);
        if ( self::exists($path) ) {
            if ( ($change = filemtime($path)) ) {
                return $change;
            }
        }
        return false;
    }

	/**
	 * Get file size value.
	 *
	 * @access public
	 * @param string $path
	 * @return int
	 */
	public static function getFileSize($path)
	{
        return (int)@filesize($path);
	}

	/**
	 * Get file size.
	 *
	 * @access public
	 * @param string $path
	 * @param int $decimals
	 * @return string
	 */
	public static function getSize($path, $decimals = 2)
	{
        $format = ['B','KB','MB','GB','TB'];
        $size = self::getFileSize($path);
        $factor = floor((strlen(strval($size)) - 1) / 3);
        return sprintf("%.{$decimals}f", $size / pow(1024, $factor)) . @$format[$factor];
	}

	/**
	 * Get file permissions.
	 *
	 * @access public
	 * @param bool $convert
	 * @return mixed
	 */
	public static function getPermissions($path, $convert = false)
	{
		$permissions = substr(sprintf('%o',@fileperms($path)),-4);
		return ($convert) ? intval($permissions) : $permissions;
	}

	/**
	 * Add string to file.
	 *
	 * @access public
	 * @param string $input
	 * @return void
	 */
	public static function addString($path, $input = '')
	{
		$handle = fopen($path,'a');
		fwrite($handle,(string)$input);
		fclose($handle);
	}

	/**
	 * Add break to file.
	 *
	 * @access public
	 * @param string $path
	 * @return void
	 */
	public static function addBreak($path)
	{
		$handle = fopen($path,'a');
		fwrite($handle,PHP_EOL);
		fclose($handle);
	}

	/**
	 * Remove file.
	 *
	 * @access public
	 * @param string $path
	 * @return bool
	 */
	public static function remove($path)
	{
		if ( self::isFile($path) ) {
			return @unlink($path);
		}
		return false;
	}

	/**
	 * Copy file.
	 *
	 * @access public
	 * @param string $path
	 * @param string $to
	 * @param resource $context
	 * @return bool
	 */
    public static function copy($path, $to, $context = null)
    {
    	$dir = dirname($to);
    	if ( self::exists($path) && self::isDir($dir) ) {
	        return copy($path,$to,$context);
    	}
        return false;
    }

	/**
	 * Move file.
	 *
	 * @access public
	 * @param string $path
	 * @param string $to
	 * @param resource $context
	 * @return bool
	 */
    public static function move($path, $to, $context = null)
    {
    	$dir = dirname($to);
    	if ( self::exists($path) && self::isDir($dir) ) {
	        return rename($path,$to,$context);
    	}
        return false;
    }

	/**
	 * Check whether path is regular file.
	 *
	 * @access public
	 * @param string $path
	 * @return bool
	 */
    public static function isFile($path)
    {
		if ( self::exists($path) ) {
			return @is_file($path);
		}
		return false;
    }

	/**
	 * Check file empty.
	 *
	 * @access public
	 * @param string $path
	 * @return mixed
	 */
	public static function isEmpty($path)
	{
		if ( self::exists($path) ) {
			return (self::getFileSize($path) == 0);
		}
		return null;
	}

	/**
	 * Check file readable.
	 *
	 * @access public
	 * @param string $path
	 * @return bool
	 */
	public static function isReadable($path)
	{
		return is_readable($path);
	}

	/**
	 * Check file writable.
	 *
	 * @access public
	 * @param string $path
	 * @return bool
	 */
	public static function isWritable($path)
	{
		return is_writable($path);
	}
	
    /**
     * Add directory.
	 *
	 * @access public
	 * @param string $path
	 * @param int $p permissions
	 * @param bool $r recursive
	 * @param resource $context
	 * @return bool
	 */
    public static function addDir($path, $p = 0755, $r = true, $context = null)
    {
    	if ( !self::isFile($path) && !self::isDir($path) ) {
    		if ( TypeCheck::isResource($context) ) {
    			return @mkdir($path,$p,$r,$context);
    		}
    		return @mkdir($path,$p,$r);
    	}
        return false;
    }

    /**
     * Check directory.
	 *
	 * @access public
	 * @param string $path
	 * @return bool
	 */
    public static function isDir($path)
    {
    	if ( self::exists($path) && is_dir($path) ) {
    		return true;
    	}
        return false;
    }

    /**
     * Remove directory.
	 *
	 * @access public
	 * @param string $dir
	 * @return bool
	 */
    public static function removeDir($path)
    {
    	if ( self::isDir($path) ) {
    		return @rmdir($path);
    	}
        return false;
    }

    /**
     * Clear directory from content.
	 *
	 * @access public
	 * @param string $path
	 * @return bool
	 */
    public static function clearDir($path)
    {
		$handler = false;
		if ( self::isDir($path) ) {
			$handler = @opendir($path);
		}
		if ( !TypeCheck::isResource($handler) ) {
			return false;
		}
	   	while( $file = readdir($handler) ) {
			if ( $file !== '.' && $file !== '..' ) {
			    if ( !self::isDir("{$path}/{$file}") ) {
			    	self::remove("{$path}/{$file}");
			    } else {
			    	$dir = "{$path}/{$file}";
				    foreach( @scandir($dir) as $file ) {
				        if ( '.' === $file || '..' === $file ) {
				        	continue;
				        }
				        if ( self::isDir("{$dir}/{$file}") ) {
				        	self::recursiveRemove("{$dir}/{$file}");
				        } else {
				        	self::remove("{$dir}/{$file}");
				        }
				    }
				    self::removeDir($dir);
			    }
			}
	   }
	   closedir($handler);
	   return true;
    }

	/**
	 * @access private
	 * @param string $path
	 * @return void
	 */
	private static function recursiveRemove($path)
	{
		if ( self::isDir($path) ) {
			$objects = @scandir($path);
			foreach ($objects as $object) {
				if ( $object !== '.' && $object !== '..' ) {
					if ( self::isDir("{$path}/{$object}") ) {
						self::recursiveRemove("{$path}/{$object}");
					} else {
						self::remove("{$path}/{$object}");
					}
				}
			}
			reset($objects);
			self::removeDir($path);
		}
	}

	/**
	 * Check path exists (file|directory).
	 *
	 * @access public
	 * @param string $path
	 * @return bool
	 */
	public static function exists($path)
	{
		clearstatcache();
		return file_exists($path);
	}

	/**
	 * Read entire file into a string.
	 *
	 * @access public
	 * @param string $path
	 * @param bool $inc (Use include path)
	 * @param resource|array $context
	 * @param int $offset
	 * @return string|false
	 */
	public static function r($path, $inc = false, $context = null, $offset = 0)
	{
		if ( TypeCheck::isStream($path) ) {
			if ( TypeCheck::isArray($context) ) {
				$context = stream_context_create($context);
			}
		}
		return @file_get_contents($path,$inc,$context,$offset);
	}

	/**
	 * Write file.
	 *
	 * @access public
	 * @param string $path
	 * @param mixed $input
	 * @param string $append
	 * @return mixed
	 */
	public static function w($path, $input = '', $append = false)
	{
		$flag = 0;
		if ( $append ) {
			$flag = FILE_APPEND;
			$input .= PHP_EOL;
		}
		return @file_put_contents($path,$input,$flag);
	}

	/**
	 * Scan path.
	 * 
	 * SCANDIR_SORT_ASCENDING : 0
	 * SCANDIR_SORT_DESCENDING : 1
	 * SCANDIR_SORT_NONE : 2
	 *
	 * @access public
	 * @param string $path
	 * @param int $sort
	 * @param array $except
	 * @return array
	 */
	public static function scanDir($path = '.', $sort = 0, $except = [])
	{
		$except = Arrayify::merge(['.', '..'],$except);
		return Arrayify::diff(@scandir($path,$sort),$except);
	}

	/**
	 * Index path files.
	 *
	 * @access public
	 * @param string $path
	 * @return mixed
	 */
	public static function index($path)
	{
		if ( self::isDir($path) ) {
			$files = glob(Stringify::formatPath("{$path}/*.*"));
			return Arrayify::combine(
				$files,array_map('filectime',$files)
			);
		}
		return false;
	}

	/**
	 * Get last created file path.
	 *
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function last($path)
	{
		if ( self::isDir($path) ) {
			$files = self::index($path);
			arsort($files);
			return (string)key($files);
		}
		return false;
	}

	/**
	 * Get first created file path.
	 *
	 * @access public
	 * @param string $path
	 * @return string
	 */
	public static function first($path)
	{
		if ( self::isDir($path) ) {
			$files = self::index($path);
			asort($files);
			return (string)key($files);
		}
		return false;
	}

	/**
	 * Get files count.
	 *
	 * @access public
	 * @param string $path
	 * @return mixed
	 */
	public static function count($path = '.')
	{
		if ( self::isDir($path) ) {
			$files = self::index($path);
			return (int)count($files);
		}
		return false;
	}

	/**
	 * Parse ini file.
	 *
	 * @access public
	 * @param string $path
	 * @param bool $sections
	 * @param int $mode
	 * @return mixed
	 */
	public static function parseIni($path, $sections = false, $mode = INI_SCANNER_NORMAL)
	{
		return parse_ini_file(
			Stringify::formatPath($path),$sections,$mode
		);
	}

	/**
	 * Import file from url.
	 * 
	 * @access public
	 * @param string $url
	 * @param string $path
	 * @return bool
	 */
	public static function import($url, $path)
	{
		$tmp = @fopen($path,'w');
		$status = fwrite($tmp,(string)self::r($url));
		fclose($tmp);
		return (bool)$status;
	}

	/**
	 * Download file.
	 *
	 * @access public
	 * @param string $path
	 * @param bool $remove
	 * @param string $timeout
	 * @param bool $verify
	 * @return bool
	 */
	public static function download($path, $remove = true, $timeout = 300, $verify = false)
	{
		if ( self::exists($path) ) {
			if ( TypeCheck::isFunction('download_url') ) {
				download_url($path,$timeout,$verify);
				if ( $remove ) {
					self::remove($path);
				}
				return true;
			}
		}
		return false;
	}

	/**
	 * Get file mime type.
	 * 
	 * @access public
	 * @param string $filename
	 * @param array $mimes
	 * @return array
	 */
	public static function getMime($filename, $mimes = null)
	{
		return wp_check_filetype($filename,$mimes);
	}
}
