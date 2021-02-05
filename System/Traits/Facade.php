<?php

namespace System\Traits;

use System\Traits\Singleton;

trait Facade
{
	use Singleton;

	public static function __callStatic($method, $parameters)
	{
		$instance = self::getInstance();

		if (method_exists($instance, $method))
		{
			return call_user_func_array([ $instance, $method ], $parameters);
		}

		return null;
	}
}