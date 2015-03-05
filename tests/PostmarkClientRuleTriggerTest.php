<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use \Postmark\PostmarkClient;

class PostmarkClientRuleTriggerTest extends PostmarkClientBaseTest {

	//todo - teardowns to clean up rules that were created and not deleted.

	function testClientCanGetRuleTriggers() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$triggers = $client->listInboundRuleTriggers();

		$this->assertNotEmpty($triggers);
	}

	function testClientCanCreateAndDeleteRuleTriggers() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$trigger = $client->createInboundRuleTrigger('test.php+' . uniqid("", true) . '@example.com');
		$this->assertNotEmpty($trigger);

		$client->deleteInboundRuleTrigger($trigger->ID);
		//Not throwing an exception here constitutes passing.
	}
}

?>