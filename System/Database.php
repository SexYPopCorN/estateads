<?php

namespace System;

use System\Traits\Facade;
use System\Config;

final class Database
{
	use Facade;

	private const MAX_INSERT_ROWS = 1000;

	private $pdo;

	private function initialize()
	{
		try
		{
			$host       = Config::get('database.host');
			$database   = Config::get('database.database');
			$charset    = Config::get('database.charset');
			$user       = Config::get('database.user');
			$password   = Config::get('database.password');

			$options = array(
				\PDO::ATTR_EMULATE_PREPARES      => Config::get('database.emulate_prepares'),
				\PDO::ATTR_ERRMODE               => Config::get('database.error_mode'),
				\PDO::ATTR_DEFAULT_FETCH_MODE    => Config::get('database.fetch_mode')
			);

			$this->pdo = new \PDO("mysql:host={$host};dbname={$database};charset={$charset}", $user, $password, $options);
		}
		catch (PDOException $exception)
		{
			exit('System Error: could not establish PDO connection (' . $exception->getMessage() . ')');
		}
	}

	private function beginTransaction()
	{
		$this->pdo->beginTransaction();
	}

	private function commit()
	{
		$this->pdo->commit();
	}

	private function rollback()
	{
		$this->pdo->rollback();
	}

	private function query(string $sql)
	{
		$this->pdo->query($sql);
	}

	private function execute(string $sql, array $data = [])
	{
		$this->pdo->prepare($sql)->execute($data);
	}

	private function fetch(string $sql, $data = [], int $fetch_mode = \PDO::FETCH_ASSOC, bool $rekey = false, bool $group = false, int $limit = -1)
	{
		$statement	= $this->pdo->prepare($sql);
		$records	= [];

		$statement->execute($data);

		while (($row = $statement->fetch($fetch_mode)) && ($limit --))
		{
			if ($rekey)
			{
				$key = array_shift($row);

				if (sizeof($row))
				{
					if ($group)
					{
						$records[$key][] = $row;
					}
					else
					{
						$records[$key] = $row;
					}
				}
			}
			else
			{
				$records[] = $row;
			}
		}

		return $records;
	}

	private function fetchRow(string $sql, $data = [], int $fetch_mode = \PDO::FETCH_ASSOC)
	{
		if ($row = $this->fetch($sql, $data, $fetch_mode, false, false, 1))
		{
			return array_pop($row);
		}
	}

	private function fetchColumn(string $sql, $data = [])
	{
		return $this->fetch($sql, $data, \PDO::FETCH_COLUMN, false, true);
	}

	private function fetchOne(string $sql, $data = [])
	{
		return $this->fetchRow($sql, $data, \PDO::FETCH_COLUMN);
	}

	private function insertOne(string $table, array $data, array $fields = [])
	{
		if (Database::isAssocArray($data))
		{
			$fields = array_keys($data);
		}

		$sql = "INSERT INTO `$table` (`" . implode('`, `', $fields) . "`) VALUES (" . implode(', ', array_fill(0, sizeof($data), '?')) . ")";

		$statement = $this->pdo->prepare($sql);
		$statement->execute(array_values($data));
	}

	private function insertMany(string $table, array $data, array $fields)
	{
		$sql = "INSERT INTO `$table` (`" . implode ('`, `', $fields) . "`) VALUES ";

		$rows = 0;

		foreach ($data as $row)
		{
			$rows++;

			foreach ($row as $value)
			{
				$values[] = $value;
			}

			$placeholders[] = "(" . implode(', ', array_fill(0, sizeof($row), '?')) . ")";

			if ($rows >= Database::MAX_INSERT_ROWS)
			{
				$statement = $this->pdo->prepare($sql . implode(', ', $placeholders));
				$statement->execute($values);

				unset($values);
				unset($placeholders);

				$rows = 0;
			}
		}

		if ($rows > 0)
		{
			$objStatement = $this->pdo->prepare($sql . implode(', ', $placeholders));
			$objStatement->execute($values);
		}
	}

	private function getLastInsertId(string $table, $id = 'id')
	{
		return $this->fetchOne("SELECT `{$id}` FROM `$table` ORDER BY `{$id}` DESC LIMIT 1");
	}

	private static function isAssocArray(array $array)
	{
		if (sizeof($array) == 0)
		{
			return false;
		}

		return !(array_keys($array) === range(0, sizeof($array) - 1));
	}
}