<?php

namespace System;

final class ClassLoader
{
    public function register($prepend = false)
    {
        spl_autoload_register([ $this, 'load' ], true, $prepend);
    }

    public function unregister()
    {
        spl_autoload_unregister([ $this, 'load' ]);
	}
	
	private function load(string $class)
	{
		$file = str_replace('\\', DIRECTORY_SEPARATOR, $class . '.php');

		if (! file_exists($file))
		{
			exit("System error: could not find class \"{$class}\"");
		}

		require $file;
	}
}