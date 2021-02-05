<?php

namespace System;

class Request
{
	private $method;
	private $url;
	private $input;
	private $files;
	private $route;

	public function __construct()
	{
		$this->method	= $_SERVER['REQUEST_METHOD'] ?? 'GET';
		$this->url		= preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']);
		$this->input	= array_merge_recursive($_GET, $_POST);
		$this->files	= [];

		$this->getUploadedFiles();
	}

	public function input($name = null, $default = '')
	{
		if (! isset($name))
		{
			
			return $this->input;
		}

		return $this->input[$name] ?? $default;
	}

	public function file($name = null)
	{
		if (! isset($name))
		{
			return $this->files;
		}

		return $this->files[$name] ?? null;
	}

	public function setRoute($route)
	{
		$this->route = $route;
	}

	public function getMethod()
	{
		return $this->method;
	}

	public function getURL()
	{
		return $this->url;
	}

	public function getRoute()
	{
		return $this->route;
	}

	static $fileKeys = [ 'error', 'name', 'size', 'tmp_name', 'type' ];

	private function prepareUploadFiles($key, $data, &$files)
	{
		if (is_array($data))
		{
			foreach ($data as $index => &$value)
			{
				if (! isset($files[$index]))
				{
					$files[$index] = [];
				}

				$this->prepareUploadFiles($key, $value, $files[$index]);
			}

			return;
		}

		$files[$key] = $data;
	}

	private function createUploadFiles(&$files)
	{
		$keys = array_keys($files);

		sort($keys, SORT_STRING);

		if (self::$fileKeys == $keys)
		{
			// TODO: Create "UploadedFile" class
			// $files = "new File(" . $files['name'] . ")";

			return;
		}

		if (is_array($files))
		{
			foreach ($files as &$data)
			{
				$this->createUploadFiles($data);
			}
		}
	}

	private function getUploadedFiles()
	{
		foreach ($_FILES as $name => $files)
		{
			$this->files[$name] = [];

			foreach (self::$fileKeys as $key)
			{
				$this->prepareUploadFiles($key, $files[$key], $this->files[$name]);
			}
		}

		$this->createUploadFiles($this->files);
	}
}