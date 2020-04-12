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
 * Allowed to edit for plugin customization
 */

namespace VanillePlugin\lib;

use VanilleNameSpace\core\system\libraries\interfaces\OrmQueryInterface;

class OrmQuery implements OrmQueryInterface
{
	public $query = [];

	/**
	 * Construct Basic ORM
	 *
	 * @param array $query
	 */
	public function __construct($query = [])
	{
		$query = array_merge([

			'table'    => '',
			'column'   => '*',
			'where'    => '',
			'orderby'  => '',
			'limit'    => '',
			'isSingle' => false,
			'isRow'    => false,
			'format'   => null,
			'type'     => ARRAY_A

		], $query);

		$query['where'] = !empty($query['where'])
		? "WHERE {$query['where']}" : '';

		$query['limit'] = !empty($query['limit'])
		? "LIMIT {$query['limit']}" : '';

		$query['orderby'] = !empty($query['orderby'])
		? "ORDER BY {$query['orderby']}" : '';

		$this->query = $query;
		return $this->query;
	}
}
