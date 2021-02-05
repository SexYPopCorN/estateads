<?php

namespace System\QueryBuilder\Traits;

use System\QueryBuilder\Where;

trait HasWhere
{
	private $where;

	private function initializeHasWhere()
	{
		$this->where = new Where($this);
	}

	public function where($field_or_closure, $operator = '', $value = '')
	{
		$this->where->and($field_or_closure, $operator, $value);

		return $this;
	}

	public function orWhere($field_or_closure, $operator = '', $value = '')
	{
		$this->where->or($field_or_closure, $operator, $value);

		return $this;
	}

	public function whereIn($field, $value)
	{
		$this->where->in($field, $value);

		return $this;
	}

	public function whereLike($field, $value)
	{
		$this->where->like($field, $value);

		return $this;
	}

	public function writeWhere()
	{
		return "{$this->where}";
	}
}