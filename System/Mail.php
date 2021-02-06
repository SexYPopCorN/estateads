<?php

namespace System;

use System\View;
use System\Config;

abstract class Mail
{
	private $to;
	private $subject;

	public function send($from = null)
	{
		$from		= ($from === null) ? Config::get('mail.sender') : $from;
		$body		= $this->build();
		$headers	= [
			'From'		=> 'nikola.angerfist@gmail.com',
			'Reply-To'	=> 'nikola.angerfist@gmail.com',
			'X-Mailer'	=> 'PHP/' . phpversion()
		];

		// echo $body;

		mail($this->to, $this->subject, "{$body}", $headers);
	}

	public function to($to)
	{
		$this->to = $to;

		return $this;
	}

	public function subject($subject)
	{
		$this->subject = $subject;

		return $this;
	}

	public function view($name)
	{
		return View::create($name);
	}

	public abstract function build();
}