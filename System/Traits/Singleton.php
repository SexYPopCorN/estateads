<?php

namespace System\Traits;

trait Singleton
{
	private static $instance = null;

	private function __construct() {}

	public static function getInstance()
	{
		if (self::$instance === null)
		{
			$Class = static::class;

			self::$instance = new $Class;

			if (method_exists(self::$instance, 'initialize'))
			{
				call_user_func_array([ self::$instance, 'initialize' ], []);
			}
		}

		return self::$instance;
	}
}