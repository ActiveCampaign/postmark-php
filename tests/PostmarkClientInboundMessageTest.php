<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use \Postmark\PostmarkClient;

class PostmarkClientInboundMessageTest extends PostmarkClientBaseTest {

	function testClientCanSearchInboundMessages() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$messages = $client->getInboundMessages(10);

		$this->assertNotEmpty($messages);
		$this->assertCount(10, $messages->InboundMessages);
	}

	function testClientCanGetInboundMessageDetails() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$retrievedMessages = $client->getInboundMessages(10);
		$baseMessageId = $retrievedMessages->InboundMessages[0]["MessageID"];
		$message = $client->getInboundMessageDetails($baseMessageId);

		$this->assertNotEmpty($message);
	}
}
?>