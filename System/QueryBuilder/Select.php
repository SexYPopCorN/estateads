<?php

namespace System\QueryBuilder;

use System\Traits\InitializeTraits;
use System\QueryBuilder\Traits\HasWhere;

use System\Database;
use System\QueryBuilder\Join;

class Select
{
	use HasWhere;
	use InitializeTraits;

	private $table;
	private $alias;
	private $joins = [];
	private $model;
	private $fields = [];
	private $limit;

	public function __construct($table)
	{
		$this->table = $table;

		$this->initializeTraits();
	}

	public function asModel($model)
	{
		$this->model = $model;

		return $this;
	}

	public function as($alias)
	{
		$this->alias = $alias;

		return $this;
	}

	public function select($fields)
	{
		$this->fields = array_unique(array_merge($this->fields, $fields));

		return $this;
	}

	public function limit($limit)
	{
		$this->limit = $limit;

		return $this;
	}

	public function join($table)
	{
		return $this->joins[] = new Join($this, $table);
	}

	public function leftJoin($table)
	{
		return $this->joins[] = new Join($this, $table, 'LEFT');
	}

	public function rightJoin($table)
	{
		return $this->joins[] = new Join($this, $table, 'RIGHT');
	}

	public function get()
	{
		$records = Database::fetch("{$this}", [], \PDO::FETCH_ASSOC);

		if (isset($this->model))
		{
			try
			{
				foreach ($records as &$record)
				{
					$model = new $this->model;

					$model->setAttributes($record);

					$record = $model;
				}
			}
			catch (Exception $exception) {}
		}

		return $records;
	}

	public function one()
	{
		$record = Database::fetchRow("{$this}", [], \PDO::FETCH_ASSOC);

		if (isset($this->model) && isset($record))
		{
			try
			{
				$model = new $this->model;

				$model->setAttributes($record);

				return $model;
			}
			catch (Exception $exception) {}
		}

		return $record;
	}

	public function __toString()
	{
		$fields = (sizeof($this->fields) > 0) ? implode(', ', $this->fields) : '*';

		$sql = "SELECT {$fields} FROM {$this->table}";

		if (strlen($this->alias))
		{
			$sql = "({$sql}) AS {$this->alias}";
		}

		if (sizeof($this->joins))
		{
			foreach ($this->joins as $join)
			{
				$sql .= " {$join}";
			}
		}

		$sql .= $this->writeWhere();

		if (isset($this->limit) && $this->limit > 0)
		{
			$sql .= " LIMIT {$this->limit}";
		}

		return $sql;
	}
}