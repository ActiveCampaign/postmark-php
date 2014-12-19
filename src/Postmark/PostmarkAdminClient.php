<?php

namespace Postmark;

class PostmarkAdminClient extends PostmarkClientBase {
	function __construct($account_token){
		parent::__construct($account_token, "X-Postmark-Account-Token");
	}
}

?>