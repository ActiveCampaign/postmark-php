<?php

namespace Postmark;

abstract class PostmarkClientBase{
	protected $authorization_token = NULL;
	protected $authorization_header = NULL;

	protected function __construct($token, $header){
		$this->authorization_header = $header;
		$this->authorization_token = $token;
	}
}

?>