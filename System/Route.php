<?php

namespace System;

use System\Config;

class Route
{
	private $methods;
	private $url;
	private $controller;
	private $action;
	private $pattern;
	private $parameters = [];
	private $arguments	= [];

	public function __construct($methods, $url, $controller, $action)
	{
		
		$this->methods		= $methods;
		$this->url			= Route::format(Config::get('app.name') . '/' . $url);
		$this->controller	= $controller;
		$this->action		= $action;
	}

	public function matchMethod($method)
	{
		return in_array($method, $this->methods);
	}

	public function matchURL($url)
	{
		$this->compile();

		$matches = [];

		if (preg_match($this->pattern, Route::format($url), $matches))
		{
			foreach ($this->parameters as $parameter)
			{
				$this->arguments[] = $matches[$parameter] ?? null;
			}

			return true;
		}

		return false;
	}

	public function doAction($request)
	{
		array_unshift($this->arguments, $request);

		try
		{
			$controller = new $this->controller;

			if (method_exists($controller, $this->action))
			{
				return call_user_func_array([ $controller, $this->action ], $this->arguments);
			}

			exit("System error: controller \"{$this->controller}\" does not implement method \"{$this->action}\"");
		}
		catch (Exception $exception)
		{
			exit("System error: could not instantiate controller \"{$this->controller}\"");
		}
	}

	private function compile()
	{
		$this->pattern = preg_replace_callback('/\{(?<parameter>[A-Z0-9-_)]+\??)\}(\/+)/i', function($match)
		{
			$parameter = $match['parameter'];

			if (substr($parameter, -1) === '?')
			{
				$parameter = substr($parameter, 0, -1);

				$this->parameters[] = $parameter;

				return "(?:(?<{$parameter}>[A-Z0-9-_]+)/)?";
			}

			$this->parameters[] = $parameter;

			return "(?<{$parameter}>[A-Z0-9-_]+)/";
		}, $this->url);

		$this->pattern = '/^' . preg_replace('/\/+/', '\/', $this->pattern) .'$/i';
	}

	private static function format($url)
	{
		$url = preg_replace('/[\/]+/', '/', $url);
		$url = preg_replace('/^\//', '', $url);
		$url = preg_replace('/([^\/])$/', '\0' . '/', $url);

		return $url;
	}
}