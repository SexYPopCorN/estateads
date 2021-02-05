<?php

namespace System\QueryBuilder;

use System\QueryBuilder\Column;

class Condition
{
	private $field;
	private $operator;
	private $value;

	public function __construct($field, $operator, $value)
	{
		$this->field	= $field;
		$this->operator	= $operator;
		$this->value	= $value;
	}

	public function __toString()
	{
		self::formatValue($this->value);

		return "{$this->field} {$this->operator} {$this->value}";
	}

	private static function formatValue(&$value)
	{
		if (is_object($value) && $value instanceof Column)
		{
			$value = "{$value}";

			return;
		}

		if (is_string($value))
		{
			$value = "'{$value}'";

			return;
		}

		if (is_array($value))
		{
			foreach ($value as &$scalar)
			{
				self::formatValue($scalar);
			}

			$value = '(' . implode(', ', $value) . ')';
		}
	}
}