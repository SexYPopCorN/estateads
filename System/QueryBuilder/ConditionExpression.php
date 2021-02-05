<?php

namespace System\QueryBuilder;

class ConditionExpression
{
	private $expressions = [];

	public function addCondition($operator, $condition)
	{
		$expression = (object) [];

		$expression->operator	= $operator;
		$expression->condition	= $condition;

		$this->expressions[] = $expression;
	}

	public function __toString()
	{
		if (sizeof($this->expressions) === 0)
		{
			return '';
		}

		unset($this->expressions[0]->operator);

		$sql = '(';

		foreach ($this->expressions as $expression)
		{
			$sql .= ($expression->operator ?? '') . ' ' . $expression->condition . ' ';
		}

		return $sql . ')';
	}
}