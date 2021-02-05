<?php

namespace Application\Controllers;

use System\Controller;
use System\View;
use Application\Models\EstateAd;

class ApplicationController extends Controller
{
	public function index($request)
	{
		return View::create('index');
	}

	public function getAds($request)
	{
		$title	= $request->input('title');
		$ads	= EstateAd::getByTitle($title);

		return json_encode($ads);
	}

	public function sendMail($request)
	{

	}
}