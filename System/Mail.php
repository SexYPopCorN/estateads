<?php

namespace System;

use System\View;
use System\Config;

abstract class Mail
{
	private $to;
	private $from;
	private $subject;

	public function send()
	{
		$from		= ($this->from === null) ? Config::get('mail.sender') : $this->from;
		$body		= $this->build();
		$headers	= [
			'MIME-Version'	=> '1.0',
			'Content-type'	=> 'text/html;charset=UTF-8',
			'From'			=> "{$from}",
			'Reply-To'		=> "{$from}",
			'X-Mailer'		=> 'PHP/' . phpversion()
		];

		mail($this->to, $this->subject, "{$body}", $headers);
	}

	public function to($to)
	{
		$this->to = $to;

		return $this;
	}

	public function from($from)
	{
		$this->from = $from;

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