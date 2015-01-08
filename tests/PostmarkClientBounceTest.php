<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use \Postmark\PostmarkClient;

class PostmarkClientBounceTest extends PostmarkClientBaseTest {

	function testClientCanSendBasicMessage() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_INBOUND_TEST_SERVER_TOKEN);

		$stats = $client->getDeliveryStatistics();
		$this->assertNotEmpty($stats, 'Stats from getDeliveryStatistics() should never be null or empty.');
		$this->assertGreaterThan(0, $stats->InactiveMails, "The inactive mail count should be greater than zero.");
	}

	function testClientCanGetBounces() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_INBOUND_TEST_SERVER_TOKEN);

		$bounces = $client->getBounces(10, 0);
		$this->assertNotEmpty($bounces);
	}

	function testClientCanGetBounce() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_INBOUND_TEST_SERVER_TOKEN);
		$bounces = $client->getBounces(10, 0);
		$id = $bounces->Bounces[0]['ID'];
		$bounce = $client->getBounce($id);
		$this->assertNotEmpty($bounce);
	}

	function testClientCanGetBounceDump() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_INBOUND_TEST_SERVER_TOKEN);
		$bounces = $client->getBounces(10, 0);
		$id = $bounces->Bounces[0]['ID'];
		$dump = $client->getBounceDump($id);
		$this->assertNotEmpty($dump);
	}

	function testClientCanGetBounceTags() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_INBOUND_TEST_SERVER_TOKEN);
		$tags = $client->getBounceTags();
		$this->assertNotEmpty($tags);
		$this->assertGreaterThan(0, count($tags));
	}

}
?>