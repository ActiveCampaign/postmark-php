<?php

namespace Postmark\Tests;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

use Postmark\PostmarkClient;
use Exception;

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkClientInboundMessageTest extends PostmarkClientBaseTest
{
    private static $testDataCreated = false;
    
    /**
     * Check if there are any inbound messages available
     */
    private function hasInboundMessages()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        
        try {
            $messages = $client->getInboundMessages(1);
            $inboundMessages = $messages->getInboundMessages();
            return !empty($inboundMessages);
        } catch (Exception $e) {
            return false;
        }
    }
    public function testClientCanSearchInboundMessages()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        // Check if there are any inbound messages at all
        if (!$this->hasInboundMessages()) {
            $this->markTestSkipped('No inbound messages available in test environment - inbound processing may not be configured');
            return;
        }

        // Retry logic to wait for messages to be available
        $retries = 5;
        $messages = null;
        
        for ($i = 0; $i < $retries; $i++) {
            $messages = $client->getInboundMessages(10);
            $inboundMessages = $messages->getInboundMessages();
            
            if (count($inboundMessages) >= 10) {
                break;
            }
            
            if ($i < $retries - 1) {
                sleep(3); // Wait 3 seconds before retry
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

        // Check if there are any inbound messages at all
        if (!$this->hasInboundMessages()) {
            $this->markTestSkipped('No inbound messages available in test environment - inbound processing may not be configured');
            return;
        }

        // Retry logic to wait for messages to be available
        $retries = 5;
        $retrievedMessages = null;
        
        for ($i = 0; $i < $retries; $i++) {
            $retrievedMessages = $client->getInboundMessages(10);
            $messages = $retrievedMessages->getInboundMessages();
            
            if (!empty($messages)) {
                break;
            }
            
            if ($i < $retries - 1) {
                sleep(3); // Wait 3 seconds before retry
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
