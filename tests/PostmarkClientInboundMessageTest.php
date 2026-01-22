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
    public function testClientCanSearchInboundMessages()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $messages = $client->getInboundMessages(10);

        $this->assertNotEmpty($messages);
        if (empty($messages->getInboundMessages())) {
            $this->markTestSkipped('No inbound messages available for testing');
            return;
        }
        $this->assertGreaterThan(0, $messages->getTotalCount());
        $this->assertNotEmpty($messages->getInboundMessages());
    }

    public function testClientCanGetInboundMessageDetails()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $retrievedMessages = $client->getInboundMessages(10);
        $inboundMessages = $retrievedMessages->getInboundMessages();
        
        if (empty($inboundMessages)) {
            $this->markTestSkipped('No inbound messages available for testing');
            return;
        }
        
        $baseMessageId = $inboundMessages[0]->getMessageID();
        $message = $client->getInboundMessageDetails($baseMessageId);

        $this->assertNotEmpty($message);
    }
}
