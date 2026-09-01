<?php

namespace Postmark\Tests;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

use Postmark\PostmarkAdminClient;

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkAdminClientDataRemovalTest extends PostmarkClientBaseTest
{
    protected function setUp(): void
    {
        parent::setUp();

        // hasAnyCredentials() in the base setUp passes when ANY ONE of six tokens is set, so a
        // partially-configured environment reaches the client constructor and fatals with
        // "Argument #1 ($serverToken) must be of type string, null given". Guard the token this
        // class actually uses so it skips with a name instead.
        $this->requireKeys('WRITE_ACCOUNT_TOKEN');
    }

    public function testClientCanCreateRequest()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $drr = $client->createDataRemovalRequest('test@activecampaign.com', 'test2@activecampaign.com', true);

        $this->assertNotEmpty($drr->getID());
        $this->assertNotEmpty($drr->getStatus());
    }

    public function testCheckDataRemovalRequest()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $createRequest = $client->createDataRemovalRequest('test@activecampaign.com', 'test2@activecampaign.com', true);
        // Wait for replication to complete
        sleep(10);
        $checkRequest = $client->getDataRemoval($createRequest->getID());

        $this->assertNotEmpty($checkRequest->getID());
        $this->assertNotEmpty($checkRequest->getStatus());
        $this->assertEquals($checkRequest->getID(), $createRequest->getID());
    }
}
