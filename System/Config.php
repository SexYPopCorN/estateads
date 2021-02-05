<?php

namespace System;

use System\Traits\Facade;

final class Config
{
	use Facade;

	private $items;

	private function initialize()
	{
		$this->items = [];

		$files = scandir(CONFIG);

		foreach ($files as $file)
		{
			$path		= CONFIG . DIRECTORY_SEPARATOR . $file;
			$extension	= pathinfo($path, PATHINFO_EXTENSION);
			$name		= pathinfo($path, PATHINFO_FILENAME); 

			if ((file_exists($path)) && ($extension == 'php'))
			{
				$config[$name] = require $path;
			}
		}

		if (is_array($config) && sizeof($config))
		{
			foreach ($config as $name => $data)
			{
				foreach ($data as $key => $value)
				{
					$this->items["{$name}.{$key}"] = $value;
				}
			}
		}
	}

	private function get(string $key)
	{
		if (isset($this->items[$key]))
		{
			return $this->items[$key];
		}

		return null;
	}
}