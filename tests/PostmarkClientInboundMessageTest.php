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
		$this->assertCount(10, $messages->getInboundMessages());
	}

	function testClientCanGetInboundMessageDetails() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$retrievedMessages = $client->getInboundMessages(10);
        fwrite(STDERR, "-------------------------!!! ". print_r($retrievedMessages, TRUE));
        fwrite(STDERR, "!!!-------------------------  ". print_r($retrievedMessages->getInboundMessages()[0], TRUE));
		$baseMessageId = $retrievedMessages->getInboundMessages()[0]->getMessageID();
		$message = $client->getInboundMessageDetails($baseMessageId);

		$this->assertNotEmpty($message);
	}
}