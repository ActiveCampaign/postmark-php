<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use \Postmark\PostmarkClient;

class PostmarkClientInboundMessageTest extends PostmarkClientBaseTest {
	
	function testClientCanSearchInboundMessages() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN);

		$messages = $client->getInboundMessages(NULL, NULL, NULL, NULL, NULL, NULL, 10);
		
		$this->assertNotEmpty($messages);
		$this->assertCount(10, $messages->InboundMessages);
	}


	function testClientCanGetInboundMessageDetails() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN);

		$retrievedMessages = $client->getInboundMessages(NULL, NULL, NULL, NULL, NULL, NULL, 10);
		$baseMessageId = $retrievedMessages->InboundMessages[0]["MessageID"];
		$message = $client->getInboundMessageDetails($baseMessageId);

		$this->assertNotEmpty($message);
	}
}
?>