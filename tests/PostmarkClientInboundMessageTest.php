<?php

namespace Postmark\Tests;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

use Postmark\PostmarkClient;

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkClientInboundMessageTest extends PostmarkClientBaseTest
{
    protected function setUp(): void
    {
        parent::setUp();

        // hasAnyCredentials() in the base setUp passes when ANY ONE of six tokens is set, so a
        // partially-configured environment reaches the client constructor and fatals with
        // "Argument #1 ($serverToken) must be of type string, null given". Guard the token this
        // class actually uses so it skips with a name instead.
        $this->requireKeys('READ_SELENIUM_TEST_SERVER_TOKEN');
    }

    public function testClientCanSearchInboundMessages()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $messages = $client->getInboundMessages(10);

        $this->assertNotEmpty($messages);

        if (0 === count($messages->getInboundMessages())) {
            $this->markTestSkipped(
                'The test server has no inbound messages, so this asserts nothing. '
                . 'This is missing fixture data on the shared test account, not an SDK fault.'
            );
        }

        $this->assertCount(10, $messages->getInboundMessages());
    }

    public function testClientCanGetInboundMessageDetails()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $retrievedMessages = $client->getInboundMessages(10);
        $inboundMessages = $retrievedMessages->getInboundMessages();
        
        if (empty($inboundMessages)) {
            $this->markTestSkipped('No inbound messages available for testing');
        }
        
        $baseMessageId = $inboundMessages[0]->getMessageID();
        $message = $client->getInboundMessageDetails($baseMessageId);

        $this->assertNotEmpty($message);
    }
}
