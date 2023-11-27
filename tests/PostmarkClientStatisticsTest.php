<?php

namespace Postmark\Tests;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

use Postmark\PostmarkClient;

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkClientStatisticsTest extends PostmarkClientBaseTest
{
    public function testClientCanGetMessageOpens()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_OPEN_TRACKING_TOKEN, $tk->TEST_TIMEOUT);

        $stats = $client->getOpenStatistics();
        $this->assertNotEmpty($stats);
    }

    public function testClientCanGetMessageOpensForSpecificMessage()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_OPEN_TRACKING_TOKEN, $tk->TEST_TIMEOUT);

        $stats = $client->getOpenStatistics();

        $messageId = $stats->getOpens()[0]->getMessageID();
        $result = $client->getOpenStatisticsForMessage($messageId);

        $this->assertNotEmpty($result);
    }

    //    function testClientCanGetMessageClicks() {
    //        $tk = parent::$testKeys;
    //        $client = new PostmarkClient($tk->READ_SELENIUM_OPEN_TRACKING_TOKEN, $tk->TEST_TIMEOUT);
    //
    //        $stats = $client->getClickStatistics();
    //        $this->assertNotEmpty($stats);
    //    }
    //
    //    function testClientCanGetMessageClickForSpecificMessage() {
    //        $tk = parent::$testKeys;
    //        $client = new PostmarkClient($tk->READ_SELENIUM_OPEN_TRACKING_TOKEN, $tk->TEST_TIMEOUT);
    //
    //        $stats = $client->getClickStatistics();
    //
    //        $messageId = $stats->getClicks()[0]->getMessageID();
    //        $result = $client->getClickStatisticsForMessage($messageId);
    //
    //        $this->assertNotEmpty($result);
    //    }

    public function testClientCanGetOutboundOverviewStatistics()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $stats = $client->getOutboundOverviewStatistics();

        $this->assertNotEmpty($stats);
    }

    public function testClientCanGetOutboundSendStatistics()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $stats = $client->getOutboundSendStatistics();

        $this->assertNotEmpty($stats);
    }

    public function testClientCanGetOutboundBounceStatistics()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $stats = $client->getOutboundBounceStatistics();

        $this->assertNotEmpty($stats);
    }

    public function testClientCanGetOutboundSpamComplaintStatistics()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $stats = $client->getOutboundSpamComplaintStatistics();

        $this->assertNotEmpty($stats);
    }

    public function testClientCanGetOutboundTrackedStatistics()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $stats = $client->getOutboundTrackedStatistics();

        $this->assertNotEmpty($stats);
    }

    public function testClientCanGetOutboundOpenStatistics()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_OPEN_TRACKING_TOKEN, $tk->TEST_TIMEOUT);

        $stats = $client->getOutboundOpenStatistics();

        $this->assertNotEmpty($stats);
    }

    public function testClientCanGetOutboundPlatformStatistics()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $stats = $client->getOutboundPlatformStatistics();

        $this->assertNotEmpty($stats);
    }

    public function testClientCanGetOutboundEmailClientStatistics()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $stats = $client->getOutboundEmailClientStatistics();

        $this->assertNotEmpty($stats);
    }

    public function testClientCanGetOutboundReadTimeStatistics()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $stats = $client->getOutboundReadTimeStatistics();

        $this->assertNotEmpty($stats);
    }
}
