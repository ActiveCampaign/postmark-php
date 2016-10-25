<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use Postmark\Tests\PostmarkClientBaseTest as PostmarkClientBaseTest;
use \Postmark\PostmarkClient;

class PostmarkClickClientStatisticsTest extends PostmarkClientBaseTest {

	function testClientCanGetClickOverviewStatistics() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_LINK_TRACKING_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$stats = $client->getOutboundClickStatistics();

		$this->assertNotNull($stats);
	}

	function testClientCanGetClickBrowserFamilies() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_LINK_TRACKING_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$stats = $client->getOutboundClickBrowserFamilyStatistics();

		$this->assertNotNull($stats);
	}

	function testClientCanGetClickBrowserPlatformStatistics() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_LINK_TRACKING_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$stats = $client->getOutboundClickBrowserPlatformStatistics();

		$this->assertNotEmpty($stats);
	}

	function testClientCanGetClickLocationStatistics() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->READ_LINK_TRACKING_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$stats = $client->getOutboundClickLocationStatistics();

		$this->assertNotNull($stats);
	}
}

?>