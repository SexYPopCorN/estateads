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

			$response = $route->doAction($this->request);
		}

		echo($response);
	}
}