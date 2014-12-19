<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use \Postmark\PostmarkClient;

class PostmarkClientRuleTriggerTest extends PostmarkClientBaseTest {
	
	function testClientCanGetRuleTriggers() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN);
		//TODO
	}
}

?>