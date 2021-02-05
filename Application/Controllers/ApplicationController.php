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
		$to      = 'nikola.barac.kg@gmail.com';
		$subject = 'the subject';
		$message = 'hello';
		$headers = array(
			'From' => 'nikola.angerfist@gmail.com',
			'Reply-To' => 'nikola.angerfist@gmail.com',
			'X-Mailer' => 'PHP/' . phpversion()
		);

		mail($to, $subject, $message, $headers);
	}
}