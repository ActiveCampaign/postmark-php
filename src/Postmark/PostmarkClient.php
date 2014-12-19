<?php

namespace Postmark;

class PostmarkClient extends PostmarkClientBase {

	private $server_token = NULL;
	
	function __construct($server_token){
		parent::__construct($server_token, "X-Postmark-Server-Token");
	}
}

?>