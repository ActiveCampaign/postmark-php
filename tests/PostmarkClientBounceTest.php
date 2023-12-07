<?php

namespace Postmark\Tests;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

use Postmark\PostmarkClient;

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkClientBounceTest extends PostmarkClientBaseTest
{
    public function testClientCanGetDeliveryStatistics()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $stats = $client->getDeliveryStatistics();

        $this->assertNotEmpty($stats, 'Stats from getDeliveryStatistics() should never be null or empty.');
        $this->assertGreaterThan(0, $stats->getInactiveMails(), 'The inactive mail count should be greater than zero.');
    }

    public function testClientCanGetBounces()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $bounces = $client->getBounces(10, 0);
        $this->assertNotEmpty($bounces);
    }

    public function testClientCanGetBounce()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        $bounces = $client->getBounces(10, 0);
        $id = $bounces->getBounces()[0]->getID();
        $bounce = $client->getBounce($id);
        $this->assertNotEmpty($bounce);
    }

    public function testClientCanGetBounceDump()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        $bounces = $client->getBounces(10, 0);
        $id = $bounces->Bounces[0]->getID();
        $dump = $client->getBounceDump($id);
        $this->assertNotEmpty($dump);
    }
}
