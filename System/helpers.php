<?php

use System\Config;
use System\View;

if (! function_exists('url'))
{
	function url($url)
	{
		return Config::get('app.base_url') . '/' . $url;
	}
}

if (! function_exists('asset'))
{
	function asset($url)
	{
		return Config::get('app.base_url') . '/assets/' . $url;
	}
}

if (! function_exists('view'))
{
	function view($name)
	{
		return View::create($name);
	}
}