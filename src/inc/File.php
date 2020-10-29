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

class File
{
	/**
	 * @access protected
	 * @var string $path
	 * @var string $name
	 * @var int $size
	 * @var string $extension
	 * @var string $content
	 * @var string $parentDir
	 */
	protected $path = null;
	protected $name = null;
	protected $size = null;
	protected $extension = null;
	protected $content = null;
	protected $parentDir = null;

	/**
	 * @access private
	 * @var stream $handler
	 */
	private $handler = null;

	/**
	 * @param string $path null
	 * @return void
	 */
	public function __construct($path = null)
	{
		if ( ($this->path = $path) ) {
			$this->analyze();
		}
	}

	/**
	 * Set file
	 *
	 * @access public
	 * @param string $path
	 * @return void
	 */
	public function set($path)
	{
		$this->path = $path;
		$this->analyze();
	}

	/**
	 * Analyze file
	 *
	 * @access protected
	 * @param void
	 * @return void
	 */
	protected function analyze()
	{
		$this->path = Stringify::formatPath($this->path);
		$this->parentDir = dirname($this->path);
		$this->extension = pathinfo($this->path, PATHINFO_EXTENSION);
		$file = Stringify::replace(".{$this->extension}", '', $this->path);
		$this->name = basename($file);
		$this->extension = strtolower($this->extension);
		$this->size = @filesize($this->path);
	}

	/**
	 * Open file stream
	 *
	 * @access protected
	 * @param string $mode
	 * @param boolean $include false
	 * @return mixed
	 */
	protected function open($mode = 'c+', $include = false)
	{
		clearstatcache();
		$this->handler = @fopen($this->path,$mode,$include);
		return $this->handler;
	}

	/**
	 * Close file stream
	 *
	 * @access protected
	 * @param void
	 * @return void
	 */
	protected function close()
	{
		if ( $this->handler ) {
			fclose($this->handler);
			$this->handler = null;
		}
		clearstatcache();
	}

	/**
	 * Get Parent Dir
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getParentDir()
	{
		return $this->parentDir;
	}

	/**
	 * Get File Extension
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getExtension()
	{
        return $this->extension;
	}

	/**
	 * Get file name
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getName()
	{
        return $this->name;
	}

	/**
	 * Get file full name
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getFileName()
	{
        return "{$this->name}.{$this->extension}";
	}
	
	/**
	 * Get file path
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getPath()
	{
        return $this->path;
	}

	/**
	 * Get file content
	 *
	 * @access public
	 * @param void
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * Get file last access
	 *
	 * @access public
	 * @param void
	 * @return mixed
	 */
    public function getLastAccess()
    {
        if ( $this->isExists() ) {
            if ( ($access = fileatime($this->path)) ) {
                return $access;
            } else {
                return false;
            }
        }
    }

	/**
	 * Get file last change
	 *
	 * @access public
	 * @param void
	 * @return mixed
	 */
    public function getLastChange()
    {
        if ( $this->isExists() ) {
            if ( ($change = filemtime($this->path)) ) {
                return $change;
            } else {
                return false;
            }
        }
    }

	/**
	 * Get file Size value
	 *
	 * @access public
	 * @param void
	 * @return int
	 */
	public function getFileSize()
	{
        return $this->size;
	}

	/**
	 * Get file Size
	 *
	 * @access public
	 * @param int $decimals 2
	 * @return string
	 */
	public function getSize($decimals = 2)
	{
        $size = ['B','KB','MB','GB','TB'];
        $factor = floor((strlen(strval($this->size)) - 1) / 3);
        return sprintf("%.{$decimals}f", $this->size / pow(1024, $factor)) . @$size[$factor];
	}

	/**
	 * Read file & get content
	 *
	 * @access public
	 * @param boolean $return false
	 * @return mixed
	 */
	public function read($return = false)
	{
		$this->open();
		if ( $this->handler && $this->isReadable() ) {
			if ( $this->isEmpty() ) {
				$this->content = '';
			} else {
				$this->content = @fread($this->handler, $this->size);
			}
			if ($return) {
				$this->close();
				return $this->content;
			}
		}
		$this->close();
	}

	/**
	 * Write file & create folder if not exists
	 *
	 * @access public
	 * @param string $input empty
	 * @return void
	 */
	public function write($input = '')
	{
		if ( !self::exists($this->parentDir) ) {
			if ( !self::addDir($this->parentDir) ) {
				return false;
			}
		}
		if ( $this->open('w', true) ) {
			fwrite($this->handler, $input);
		}
		$this->close();
	}

	/**
	 * Add string to file
	 *
	 * @access public
	 * @param string $input
	 * @return void
	 */
	public function addString($input = '')
	{
		$this->open('a');
		if ( $this->handler ) {
			fwrite($this->handler, $input);
		}
		$this->close();
	}

	/**
	 * Add space to file
	 *
	 * @access public
	 * @param void
	 * @return void
	 */
	public function addBreak()
	{
		$this->open('a');
		if ( $this->handler ) {
			fwrite($this->handler, PHP_EOL);
		}
		$this->close();
	}

	/**
	 * Remove file
	 *
	 * @access public
	 * @param void
	 * @return boolean
	 */
	public function remove()
	{
		$this->close();
		if ( $this->isExists() ) {
			if ( @unlink($this->path) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Copy file
	 *
	 * @access public
	 * @param string $path
	 * @return boolean
	 */
    public function copy($path)
    {
    	$this->close();
    	if ( $this->isExists() ) {
	        if ( copy($this->path, $path) ) {
	            return true;
	        }
    	}
        return false;
    }

	/**
	 * Move file
	 *
	 * @access public
	 * @param string $path
	 * @return boolean
	 */
    public function move($path)
    {
    	$this->close();
    	if ( $this->isExists() ) {
	        if ( rename($this->path, $path) ) {
	            return true;
	        }
    	}
        return false;
    }

	/**
	 * Check file only exists
	 *
	 * @access public
	 * @param void
	 * @return boolean
	 */
	public function isExists()
	{
		if ( file_exists($this->path) && is_file($this->path) ) {
			return true;
		}
		return false;
	}

	/**
	 * Check whether path is regular file
	 *
	 * @access public
	 * @param void
	 * @return mixed
	 */
    public function isFile()
    {
    	if ( $this->isExists() ) {
    		return is_file($this->path);
    	}
        return null;
    }

	/**
	 * Check file empty
	 *
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public function isEmpty()
	{
		if ( $this->isExists() ) {
			return ($this->size == 0);
		}
		return null;
	}

	/**
	 * Check file readable
	 *
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public function isReadable()
	{
		if ( $this->isExists() ) {
			return ($this->open('r') !== false);
		}
		return null;
	}

	/**
	 * Check file writable
	 *
	 * @access public
	 * @param void
	 * @return mixed
	 */
	public function isWritable()
	{
		if ( $this->isExists() ) {
			return is_writable($this->path);
		}
		return null;
	}
	
    /**
     * Add directory
	 *
	 * @access public
	 * @param string $path
	 * @param int $mode 0755
	 * @param boolean $recursive true
	 * @return boolean
	 */
    public static function addDir($path = null, $mode = 0755, $recursive = true)
    {
    	if ( !is_file($path) && !self::isDir($path) ) {
    		if ( @mkdir($path,$mode,$recursive) ) {
            	return true;
        	}
    	}
        return false;
    }

    /**
     * Check directory
	 *
	 * @access public
	 * @param string $path
	 * @return boolean
	 */
    public static function isDir($path = null)
    {
    	if ( file_exists($path) && is_dir($path) ) {
    		return true;
    	}
        return false;
    }

    /**
     * Remove directory
	 *
	 * @access public
	 * @param string $dir
	 * @return boolean
	 */
    public static function removeDir($path)
    {
    	if ( self::isDir($path) ) {
    		if ( @rmdir($path) ) {
            	return true;
        	}
    	}
        return false;
    }

    /**
     * Clear directory from content
	 *
	 * @access public
	 * @param string $path
	 * @return boolean
	 */
    public static function clearDir($path)
    {
		$handler = false;
		if ( self::isDir($path) ) {
			$handler = opendir($path);
		}
		if ( !$handler ) {
			return false;
		}
	   	while( $file = readdir($handler) ) {
			if ( $file !== '.' && $file !== '..' ) {
			    if ( !self::isDir("{$path}/{$file}") ) {
			    	@unlink("{$path}/{$file}");
			    } else {
			    	$dir = "{$path}/{$file}";
				    foreach( scandir($dir) as $file ) {
				        if ( '.' === $file || '..' === $file ) {
				        	continue;
				        }
				        if ( self::isDir("{$dir}/{$file}") ) {
				        	self::recursiveRemove("{$dir}/{$file}");
				        } else {
				        	@unlink("{$dir}/{$file}");
				        }
				    }
				    @rmdir($dir);
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
			$objects = scandir($path);
			foreach ($objects as $object) {
				if ( $object !== '.' && $object !== '..' ) {
					if ( filetype("{$path}/{$object}") == 'dir' ) {
						self::recursiveRemove("{$path}/{$object}");
					} else {
						@unlink("{$path}/{$object}");
					}
				}
			}
			reset($objects);
			@rmdir($path);
		}
	}

	/**
	 * Check File Exists without stream
	 *
	 * @access public
	 * @param string $path
	 * @return boolean
	 */
	public static function exists($path)
	{
		if ( file_exists($path) ) {
			return true;
		}
		return false;
	}

	/**
	 * Read file without stream
	 *
	 * @access public
	 * @param string $path
	 * @return mixed
	 */
	public static function r($path)
	{
		return @file_get_contents($path);
	}

	/**
	 * Write file without stream
	 *
	 * @access public
	 * @param string $path
	 * @param string $input empty
	 * @param string $append false
	 * @return mixed
	 */
	public static function w($path, $input = '', $append = false)
	{
		$flag = 0;
		if ($append) {
			$flag = FILE_APPEND;
		}
		return @file_put_contents($path,$input,$flag);
	}
}
