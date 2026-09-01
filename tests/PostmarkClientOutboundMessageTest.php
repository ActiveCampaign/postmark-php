<?php

namespace Postmark\Tests;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

use Postmark\PostmarkClient;

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkClientOutboundMessageTest extends PostmarkClientBaseTest
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

    public function testClientCanSearchOutboundMessages()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $messages = $client->getOutboundMessages(10);
        $this->assertNotEmpty($messages);
        if (0 === count($messages->getMessages())) {
            $this->markTestSkipped(
                'The test server has no outbound messages, so this asserts nothing. '
                . 'This is missing fixture data on the shared test account, not an SDK fault.'
            );
        }

        $this->assertCount(10, $messages->getMessages());
    }

    public function testClientCanGetOutboundMessageDetails()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $retrievedMessages = $client->getOutboundMessages(1, 50);
        $messages = $retrievedMessages->getMessages();
        
        if (empty($messages)) {
            $this->markTestSkipped('No outbound messages available for testing');
        }

        $baseMessageId = $messages[0]->getMessageID();
        $message = $client->getOutboundMessageDetails($baseMessageId);

        $this->assertNotEmpty($message);
    }

    public function testClientCanGetOutboundMessageDump()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $retrievedMessages = $client->getOutboundMessages(1, 50);
        $messages = $retrievedMessages->getMessages();
        
        if (empty($messages)) {
            $this->markTestSkipped('No outbound messages available for testing');
        }
        
        $baseMessageId = $messages[0]->getMessageID();
        $message = $client->getOutboundMessageDump($baseMessageId);

        $this->assertNotEmpty($message);
    }
}
