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
		$this->assertGreaterThan(0, $stats["InactiveMails"], "The inactive mail count should be greater than zero.");
	}

}
?>