<?php

namespace System;

class View
{
	private static $shared = [];

	private $name;
	private $file;
	private $data = [];

	protected function __construct($name)
	{
		$this->name = $name;

		$this->load();
	}

	public static function create($name)
	{
		return new View($name);
	}

	public static function share($data)
	{
		foreach ($data as $key => $value)
		{
			self::$shared[$key] = $value;
		}
	}

	public function with($data)
	{
		foreach ($data as $key => $value)
		{
			$this->data[$key] = $value;
		}
	
		return $this;
	}

	public function render()
	{
		$data = array_merge($this->data, self::$shared);

		foreach ($data as $key => $value)
		{
			$$key = $value;
		}

		ob_start();

		require $this->file;

		return ob_get_clean();
	}

	public function __toString()
	{
		return $this->render();
	}

	private function load()
	{
		$this->file = str_replace('.', DIRECTORY_SEPARATOR, $this->name);
		$this->file = VIEWS . DIRECTORY_SEPARATOR . $this->file . '.php';

		if (! file_exists($this->file))
		{
			exit("System error: could not load view \"{$this->name}\"");
		}
	}
}