<?php

namespace Postmark\Tests;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

use Postmark\PostmarkClient;

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkClickClientStatisticsTest extends PostmarkClientBaseTest
{
    protected function setUp(): void
    {
        parent::setUp();

        // hasAnyCredentials() in the base setUp passes when ANY ONE of six tokens is set, so a
        // partially-configured environment reaches the client constructor and fatals with
        // "Argument #1 ($serverToken) must be of type string, null given". Guard the token this
        // class actually uses so it skips with a name instead.
        $this->requireKeys('READ_LINK_TRACKING_TEST_SERVER_TOKEN');
    }

    public function testClientCanGetClickOverviewStatistics(): void
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_LINK_TRACKING_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $stats = $client->getOutboundClickStatistics();

        $this->assertNotNull($stats);
    }

    public function testClientCanGetClickBrowserFamilies()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_LINK_TRACKING_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $stats = $client->getOutboundClickBrowserFamilyStatistics();

        $this->assertNotNull($stats);
    }

    public function testClientCanGetClickBrowserPlatformStatistics()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_LINK_TRACKING_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $stats = $client->getOutboundClickBrowserPlatformStatistics();

        $this->assertNotEmpty($stats);
    }

    public function testClientCanGetClickLocationStatistics()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_LINK_TRACKING_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $stats = $client->getOutboundClickLocationStatistics();

        $this->assertNotNull($stats);
    }
}
