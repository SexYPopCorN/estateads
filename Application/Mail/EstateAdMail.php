<?php

namespace Application\Mail;

use System\Mail;

class EstateAdMail extends Mail
{
	private $data;

	public function __construct($data)
	{
		$this->data = $data;
	}

	public function build()
	{
		return $this->view('mail.estatead')
			->with($this->data);
	}
}