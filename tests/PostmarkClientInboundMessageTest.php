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
        $this->assertCount(10, $messages->getInboundMessages());
    }

    public function testClientCanGetInboundMessageDetails()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $retrievedMessages = $client->getInboundMessages(10);
        $baseMessageId = $retrievedMessages->getInboundMessages()[0]->getMessageID();
        $message = $client->getInboundMessageDetails($baseMessageId);

        $this->assertNotEmpty($message);
    }
}
