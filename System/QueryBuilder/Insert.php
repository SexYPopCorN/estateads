<?php

namespace System\QueryBuilder;

use System\Database;

class Insert
{
	private $table;
	private $data;

	public function __construct($table, $data, $fields = [])
	{
		$this->table	= $table;
		$this->data		= $data;
		$this->fields	= $fields;
	}

	public function execute()
	{
		
		if (Database::isAssocArray($this->data))
		{
			return Database::insertOne("{$this->table}", $this->data);
		}

		Database::insertMany("{$this->table}", $this->data, $this->fields);
	}

	public function __toString() {}
}