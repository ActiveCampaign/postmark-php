<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use \Postmark\PostmarkClient;

class PostmarkClientTagTriggerTest extends PostmarkClientBaseTest {
	
	function testClientCanGetTagTriggers() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN);

		$triggers = $client->searchTagTriggers();
		$this->assertNotEmpty($triggers);
	}


	function testClientCanCreateAndDeleteTagTriggers() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN);

		$trigger = $client->createTagTrigger('PHP-Client-Testing-Rule-' . date('c'), false);
		$this->assertNotEmpty($trigger);

		$client->deleteTagTrigger($trigger->ID);
		//Not throwing an exception here constitutes successful completion.
	}

	function testClientCanEditExistingTagTriggers() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN);

		$trigger = $client->createTagTrigger(uniqid("PHP-TEST-", true), false);
		
		$updatedTrigger = $client->editTagTrigger($trigger->ID, uniqid("PHP-TEST-", true), true);
		
		$this->assertNotSame($trigger->MatchName, $updatedTrigger->MatchName);
		$this->assertNotSame($trigger->TrackOpens, $updatedTrigger->TrackOpens);

		$client->deleteTagTrigger($trigger->ID);
		//Not throwing an exception here constitutes successful completion.
	}
}

?>