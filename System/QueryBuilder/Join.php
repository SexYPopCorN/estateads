<?php

namespace System\QueryBuilder;

use System\QueryBuilder\Traits\HasConditions;

use System\QueryBuilder\Column;

class Join
{
	use HasConditions;

	private $select;
	private $table;
	private $type;

	public function __construct($select, $table, $type = 'INNER')
	{
		$this->select	= $select;
		$this->table	= $table;
		$this->type		= $type;
	}

	public function on($field_or_closure, $operator = '', $column = '')
	{
		$this->addCondition('AND', $field_or_closure, $operator, new Column($column));

		return $this;
	}

	public function orOn($field_or_closure, $operator = '', $column = '')
	{
		$this->addCondition('OR', $field_or_closure, $operator, new Column($column));

		return $this;
	}

	public function __call($method, $params)
	{
		return call_user_func_array([ $this->select, $method ], $params);
	}

	public function __toString()
	{
		$sql = '';

		if ($this->hasConditions())
		{
			$sql = "{$this->type} JOIN {$this->table} ON " . $this->writeConditions();
		}

		return $sql;
	}
}