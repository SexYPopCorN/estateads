<?php

namespace Application\Controllers;

use System\Controller;
use System\View;
use Application\Models\EstateAd;
use Application\Mail\EstateAdMail;

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
		$to		= $request->input('email');
		$data	= $request->input();

		try
		{
			(new EstateAdMail($data))
				->to($to)
				->subject('Estate Ad')
				->send();
		}
		catch (Exception $exception)
		{
			http_response_code(500);
		}
	}
}