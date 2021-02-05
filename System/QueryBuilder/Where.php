<?php

namespace System\QueryBuilder;

use System\QueryBuilder\Traits\HasConditions;

class Where
{
	use HasConditions;

	private $select;

	public function __construct($select)
	{
		$this->select = $select;
	}

	public function and($field_or_closure, $operator = '', $value = '')
	{
		$this->addCondition('AND', $field_or_closure, $operator, $value);

		return $this;
	}

	public function or($field_or_closure, $operator = '', $value = '')
	{
		$this->addCondition('OR', $field_or_closure, $operator, $value);

		return $this;
	}

	public function in($field, $value)
	{
		$this->addCondition('AND', $field, 'IN', $value);

		return $this;
	}

	public function like($field, $value)
	{
		$this->addCondition('AND', $field, 'LIKE', $value);

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
			$sql .= ' WHERE ' . $this->writeConditions();
		}

		return $sql;
	}
}