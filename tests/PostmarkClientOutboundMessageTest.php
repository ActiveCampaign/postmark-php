<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use \Postmark\PostmarkClient;

class PostmarkClientOutboundMessageTest extends PostmarkClientBaseTest {

	function testClientCanSearchOutboundMessages() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$messages = $client->getOutboundMessages(10);
		$this->assertNotEmpty($messages);
		$this->assertCount(10, $messages->getMessages());
	}

	function testClientCanGetOutboundMessageDetails() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$retrievedMessages = $client->getOutboundMessages(1, 50);

		$baseMessageId = $retrievedMessages->getMessages()[0]->getMessageID();
		$message = $client->getOutboundMessageDetails($baseMessageId);

		$this->assertNotEmpty($message);
	}

	function testClientCanGetOutboundMessageDump() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$retrievedMessages = $client->getOutboundMessages(1, 50);
        $baseMessageId = $retrievedMessages->getMessages()[0]->getMessageID();
		$message = $client->getOutboundMessageDump($baseMessageId);

		$this->assertNotEmpty($message);
	}
}