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

final class Data extends Orm
{
    /**
     * @param void
     */
    public function __construct()
	{
		// Init plugin db
		$this->init();
	}

	/**
	 * Export data from table.
	 *
	 * @access public
	 * @param string $name
	 * @param string $column
	 * @return string
	 */
	public function export($name, $column = '')
	{
		$file = "{$this->getTempPath()}/{$name}.csv";
		$res = fopen($file, 'w');
		fputcsv($res, explode(',', $column), ';');
		$columns = explode(',', $column);
		foreach ($columns as $key => $value) {
			$value = trim($value);
			$columns[$key] = "`{$value}`";
		}
		$data = $this->select(new OrmQuery([
			'table'  => $name,
			'column' => implode(',', $columns)
		]));
		foreach ($data as $line){
			fputcsv($res, $line, ';');
		}
		fclose($res);
		return $file;
	}

	/**
	 * Reset database table.
	 *
	 * @access public
	 * @param string $name
	 * @return bool
	 */
	public function reset($name)
	{
		return $this->deleteAll($name);
	}
}
