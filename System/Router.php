<?php

namespace System;

use System\Traits\Facade;

use System\Route;

final class Router
{
	use Facade;

	private $routes = [];

	private function getMatchingRoute($request)
	{
		foreach ($this->routes as $route)
		{
			if ($route->matchMethod($request->getMethod()) && $route->matchURL($request->getURL()))
			{
				return $route;
			}
		}

		return null;
	}

	private function get($url, $controller, $action)
	{
		$this->routes[] = new Route([ 'GET', 'HEAD' ], $url, $controller, $action);
	}

	private function post($url, $controller, $action)
	{
		$this->routes[] = new Route([ 'POST' ], $url, $controller, $action);
	}
}