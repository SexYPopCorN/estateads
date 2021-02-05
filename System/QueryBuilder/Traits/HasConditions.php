<?php

namespace System\QueryBuilder\Traits;

use System\QueryBuilder\ConditionExpression;
use System\QueryBuilder\Condition;

trait HasConditions
{
	private $conditions = [];
	private $stack = [];
	private $first = true;

	private function addCondition($logic, $field_or_closure, $operator = '', $value = '')
	{
		$stack = &$this->getStack();

		if (is_callable($field_or_closure))
		{
			$expression = new ConditionExpression();

			$stack->addCondition($logic, $expression);

			array_push($this->stack, $expression);

			$field_or_closure($this);

			array_pop($this->stack);

			return;
		}

		$stack->addCondition($logic, new Condition($field_or_closure, $operator, $value));
	}

	private function &getStack()
	{
		$size = sizeof($this->stack);

		if ($size == 0)
		{
			$this->stack[] = new ConditionExpression();

			return $this->stack[0];
		}

		return $this->stack[$size - 1];
	}

	private function hasConditions()
	{
		return (sizeof($this->stack) > 0);
	}

	private function writeConditions()
	{
		return array_pop($this->stack) . '';
	}
}