<?php

namespace System\QueryBuilder;

use System\Traits\InitializeTraits;
use System\QueryBuilder\Traits\HasWhere;

use System\Database;

class Update
{
	use HasWhere;
	use InitializeTraits;

	private $table;
	private $data;

	public function __construct($table, $data)
	{
		$this->table	= $table;
		$this->data		= $data;

		$this->initializeTraits();
	}

	public function execute()
	{
		Database::execute("{$this}");
	}

	public function __toString()
	{
		$sql = "UPDATE {$this->table} SET";

		foreach ($this->data as $key => $value)
		{
			$sql .= " {$key} = '{$value}'";
		}

		$sql .= $this->writeWhere();

		return $sql;
	}
}