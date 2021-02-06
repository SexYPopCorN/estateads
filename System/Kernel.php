<?php

namespace System;

use System\Router;

class Kernel
{
	private $request;

	public function __construct()
	{
		$this->request = new Request();
	}

	public function run()
	{
		require SYSTEM . DIRECTORY_SEPARATOR . 'helpers.php';
		require APPLICATION . DIRECTORY_SEPARATOR . 'routes.php';

		$response	= '';
		$route		= Router::getMatchingRoute($this->request);

		if (isset($route))
		{
			$this->request->setRoute($route);

			echo $route->doAction($this->request);

			return;
		}

		exit("System error: no matching route found");
	}
}