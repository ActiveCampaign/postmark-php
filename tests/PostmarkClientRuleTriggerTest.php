<?php

namespace Postmark\Tests;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

use Postmark\PostmarkClient;

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkClientRuleTriggerTest extends PostmarkClientBaseTest
{
    protected function setUp(): void
    {
        parent::setUp();

        // hasAnyCredentials() in the base setUp passes when ANY ONE of six tokens is set, so a
        // partially-configured environment reaches the client constructor and fatals with
        // "Argument #1 ($serverToken) must be of type string, null given". Guard the token this
        // class actually uses so it skips with a name instead.
        $this->requireKeys('WRITE_TEST_SERVER_TOKEN');
    }

    // todo - teardowns to clean up rules that were created and not deleted.

    public function testClientCanGetRuleTriggers()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $triggers = $client->listInboundRuleTriggers();

        $this->assertNotEmpty($triggers);
    }

    public function testClientCanCreateAndDeleteRuleTriggers()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $trigger = $client->createInboundRuleTrigger('test.php+' . uniqid('', true) . '@example.com');
        $this->assertNotEmpty($trigger);

        $client->deleteInboundRuleTrigger($trigger->getID());
        // Not throwing an exception here constitutes passing.
    }
}
