<?php

namespace System\QueryBuilder;

use System\QueryBuilder\Insert;
use System\QueryBuilder\Select;
use System\QueryBuilder\Update;
use System\QueryBuilder\Delete;

class Table
{
	private $name;
	private $alias;

	public function __construct($name)
	{
		$this->name = $name;
		$this->alias = '';
	}

	public function insert($data, $fields = [])
	{
		return new Insert($this, $data, $fields);
	}

	public function select()
	{
		return new Select($this);
	}

	public function update($data)
	{
		return new Update($this, $data);
	}

	public function delete()
	{
		return new Delete($this);
	}

	public function as($alias)
	{
		$this->alias = $alias;

		return $this;
	}

	public function __toString()
	{
		$sql = "{$this->name}";

		if (strlen($this->alias))
		{
			$sql .= " AS {$this->alias}";
		}

		return $sql;
	}
}