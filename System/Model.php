<?php

namespace System;

use System\Database;
use System\QueryBuilder\Table;
use System\QueryBuilder\Select;
use System\QueryBuilder\Update;

class Model implements \JsonSerializable
{
	protected $table;
	protected $primaryKey = 'id';

	private $attributes	= [];
	private $original	= [];

	public static function create($data)
	{
		$instance	= new static;
		$table		= $instance->getTable();

		(new Table($table))
			->insert($data)
			->execute();

		return static::find(Database::getLastInsertId($table, $instance->primaryKey));
	}

	public static function createMany($data, $fields)
	{
		$instance	= new static;
		$table		= $table->getTable();
		$primaryKey	= Database::getLastInsertId($table, $instance->primaryKey);

		(new Table($table))
			->insert($data, $fields)
			->execute();

		return static::where($instance->primaryKey, '>', $primaryKey)
			->get();
	}

	public static function all()
	{
		return (new Table((new static)->getTable()))
			->select()
			->asModel(static::class)
			->get();
	}

	public static function where($field, $operator = null, $value = null)
	{
		return (new Table((new static)->getTable()))
			->select()
			->asModel(static::class)
			->where($field, $operator, $value);
	}

	public static function orWhere($field, $operator = null, $value = null)
	{
		return (new Table((new static)->getTable()))
			->select()
			->asModel(static::class)
			->orWhere($field, $operator, $value);
	}

	public static function whereIn($field, $value)
	{
		return (new Table((new static)->getTable()))
			->select()
			->asModel(static::class)
			->whereIn($field, $value);
	}

	public static function find($key)
	{
		$instance = new static;
		
		return (new Table($instance->getTable()))
			->select()
			->asModel(static::class)
			->where($instance->primaryKey, '=', $key)
			->one();
	}

	public function update($data)
	{
		$instance = new static;

		if (isset($this))
		{
			return (new Table($this->getTable()))
				->update($data)
				->where($instance->primaryKey, '=', $this->attributes[$instance->primaryKey])
				->execute();
		}

		return (new Table($instance->getTable()))
				->update($data);
	}

	public function delete()
	{
		$instance = new static;

		if (isset($this))
		{
			return (new Table($this->getTable()))
				->delete()
				->where($instance->primaryKey, '=', $this->attributes[$instance->primaryKey])
				->execute();
		}

		return (new Table($instance->getTable()))
				->delete();
	}

	public function setAttributes($attributes)
	{
		$this->attributes = $attributes;

		return $this;
	}

	public function __get($key)
	{
		if (isset($this->attributes[$key]))
		{
			return $this->attributes[$key];
		}

		return null;
	}

	public function __set($key, $value)
	{
		if (isset($this->attributes[$key]))
		{
			$this->attributes[$key] = $value;
		}
	}

	public function jsonSerialize()
    {
        return $this->attributes;
    }

	private function getTable()
	{
		if ($this->table === null)
		{
			$this->table = strtolower(preg_replace('/(?:.)(?=[A-Z])/u', '\0' . '_', static::class));
		}

		return $this->table;
	}
}