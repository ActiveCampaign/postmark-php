<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use Postmark\Tests\PostmarkClientBaseTest as PostmarkClientBaseTest;
use \Postmark\PostmarkClient;

class PostmarkClientStatisticsTest extends PostmarkClientBaseTest {

	function testClientCanGetMessageOpens() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_OPEN_TRACKING_TOKEN, $tk->TEST_TIMEOUT);

		$stats = $client->getOpenStatistics();
		$this->assertNotEmpty($stats);
	}

	function testClientCanGetMessageOpensForSpecificMessage() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_OPEN_TRACKING_TOKEN, $tk->TEST_TIMEOUT);

		$stats = $client->getOpenStatistics();

		$messageId = $stats->Opens[0]["MessageID"];
		$result = $client->getOpenStatisticsForMessage($messageId);

		$this->assertNotEmpty($result);
	}

	function testClientCanGetOutboundOverviewStatistics() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$stats = $client->getOutboundOverviewStatistics();

		$this->assertNotEmpty($stats);
	}

	function testClientCanGetOutboundSendStatistics() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$stats = $client->getOutboundSendStatistics();

		$this->assertNotEmpty($stats);
	}

	function testClientCanGetOutboundBounceStatistics() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$stats = $client->getOutboundBounceStatistics();

		$this->assertNotEmpty($stats);
	}

	function testClientCanGetOutboundSpamComplaintStatistics() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$stats = $client->getOutboundSpamComplaintStatistics();

		$this->assertNotEmpty($stats);
	}

	function testClientCanGetOutboundTrackedStatistics() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$stats = $client->getOutboundTrackedStatistics();

		$this->assertNotEmpty($stats);
	}

	function testClientCanGetOutboundOpenStatistics() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_OPEN_TRACKING_TOKEN, $tk->TEST_TIMEOUT);

		$stats = $client->getOutboundOpenStatistics();

		$this->assertNotEmpty($stats);
	}

	function testClientCanGetOutboundPlatformStatistics() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$stats = $client->getOutboundPlatformStatistics();

		$this->assertNotEmpty($stats);
	}

	function testClientCanGetOutboundEmailClientStatistics() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$stats = $client->getOutboundEmailClientStatistics();

		$this->assertNotEmpty($stats);
	}

	function testClientCanGetOutboundReadTimeStatistics() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$stats = $client->getOutboundReadTimeStatistics();

		$this->assertNotEmpty($stats);
	}
}

?>