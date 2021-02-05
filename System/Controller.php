<?php

namespace System;

abstract class Controller
{
	protected function redirect($url)
	{
		header('Location: ' . url('product/list'));
		exit(0);
	}
}