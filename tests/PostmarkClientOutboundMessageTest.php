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
    public function testClientCanSearchOutboundMessages()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $messages = $client->getOutboundMessages(10);
        $this->assertNotEmpty($messages);
        $this->assertCount(10, $messages->getMessages());
    }

    public function testClientCanGetOutboundMessageDetails()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        // Retry logic to wait for messages to be available
        $retries = 3;
        $retrievedMessages = null;
        
        for ($i = 0; $i < $retries; $i++) {
            $retrievedMessages = $client->getOutboundMessages(1, 50);
            $messages = $retrievedMessages->getMessages();
            
            if (!empty($messages)) {
                break;
            }
            
            if ($i < $retries - 1) {
                sleep(2); // Wait 2 seconds before retry
            }
        }
        
        $this->assertNotEmpty($retrievedMessages, 'No outbound messages retrieved after retries');
        $messages = $retrievedMessages->getMessages();
        $this->assertNotEmpty($messages, 'No outbound messages found in response');

        $baseMessageId = $messages[0]->getMessageID();
        $message = $client->getOutboundMessageDetails($baseMessageId);

        $this->assertNotEmpty($message);
    }

    public function testClientCanGetOutboundMessageDump()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        // Retry logic to wait for messages to be available
        $retries = 3;
        $retrievedMessages = null;
        
        for ($i = 0; $i < $retries; $i++) {
            $retrievedMessages = $client->getOutboundMessages(1, 50);
            $messages = $retrievedMessages->getMessages();
            
            if (!empty($messages)) {
                break;
            }
            
            if ($i < $retries - 1) {
                sleep(2); // Wait 2 seconds before retry
            }
        }
        
        $this->assertNotEmpty($retrievedMessages, 'No outbound messages retrieved after retries');
        $messages = $retrievedMessages->getMessages();
        $this->assertNotEmpty($messages, 'No outbound messages found in response');
        
        $baseMessageId = $messages[0]->getMessageID();
        $message = $client->getOutboundMessageDump($baseMessageId);

        $this->assertNotEmpty($message);
    }
}
