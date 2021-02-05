<?php

namespace System\QueryBuilder;

use System\Traits\InitializeTraits;
use System\QueryBuilder\Traits\HasWhere;

use System\Database;

class Delete
{
	use HasWhere;
	use InitializeTraits;

	private $table;

	public function __construct($table)
	{
		$this->table = $table;

		$this->initializeTraits();
	}

	public function execute()
	{
		Database::execute("{$this}");
	}

	public function __toString()
	{
		$sql = "DELETE FROM {$this->table}" . $this->writeWhere();

		return $sql;
	}
}