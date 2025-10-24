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

        // Retry logic to wait for messages to be available
        $retries = 3;
        $messages = null;
        
        for ($i = 0; $i < $retries; $i++) {
            $messages = $client->getInboundMessages(10);
            $inboundMessages = $messages->getInboundMessages();
            
            if (count($inboundMessages) >= 10) {
                break;
            }
            
            if ($i < $retries - 1) {
                sleep(2); // Wait 2 seconds before retry
            }
        }

        $this->assertNotEmpty($messages);
        $inboundMessages = $messages->getInboundMessages();
        $this->assertGreaterThanOrEqual(10, count($inboundMessages), 'Expected at least 10 inbound messages after retries');
    }

    public function testClientCanGetInboundMessageDetails()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        // Retry logic to wait for messages to be available
        $retries = 3;
        $retrievedMessages = null;
        
        for ($i = 0; $i < $retries; $i++) {
            $retrievedMessages = $client->getInboundMessages(10);
            $messages = $retrievedMessages->getInboundMessages();
            
            if (!empty($messages)) {
                break;
            }
            
            if ($i < $retries - 1) {
                sleep(2); // Wait 2 seconds before retry
            }
        }
        
        $this->assertNotEmpty($retrievedMessages, 'No inbound messages retrieved after retries');
        $messages = $retrievedMessages->getInboundMessages();
        $this->assertNotEmpty($messages, 'No inbound messages found in response');
        
        $baseMessageId = $messages[0]->getMessageID();
        $message = $client->getInboundMessageDetails($baseMessageId);

        $this->assertNotEmpty($message);
    }
}
