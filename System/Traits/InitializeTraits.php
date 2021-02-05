<?php

namespace System\Traits;

trait InitializeTraits
{
	private function initializeTraits()
	{
		$traits = class_uses($this, true);

		foreach ($traits as $trait)
		{
			$chunks	= explode('\\', $trait);
			$name	= array_pop($chunks);
			$method	= "initialize{$name}";

			if (method_exists($this, $method))
			{
				call_user_func_array([ $this, $method ], []);
			}
		}
	}
}