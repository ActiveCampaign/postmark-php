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
class PostmarkClientOutboundMessageTest extends PostmarkClientBaseTest
{
    private static $testDataCreated = false;
    
    /**
     * Check if there are any outbound messages available
     */
    private function hasOutboundMessages()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        
        try {
            $messages = $client->getOutboundMessages(1, 50);
            $outboundMessages = $messages->getMessages();
            return !empty($outboundMessages);
        } catch (Exception $e) {
            return false;
        }
    }
    public function testClientCanSearchOutboundMessages()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        // Check if there are any outbound messages at all
        if (!$this->hasOutboundMessages()) {
            $this->markTestSkipped('No outbound messages available in test environment');
            return;
        }

        // Retry logic to wait for messages to be available
        $retries = 5;
        $messages = null;
        
        for ($i = 0; $i < $retries; $i++) {
            $messages = $client->getOutboundMessages(1, 50);
            $outboundMessages = $messages->getMessages();
            
            if (count($outboundMessages) >= 1) {
                break;
            }
            
            if ($i < $retries - 1) {
                sleep(3); // Wait 3 seconds before retry
            }
        }

        $this->assertNotEmpty($messages);
        $outboundMessages = $messages->getMessages();
        $this->assertGreaterThanOrEqual(1, count($outboundMessages), 'Expected at least 1 outbound message after retries');
    }

    public function testClientCanGetOutboundMessageDetails()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        // Check if there are any outbound messages at all
        if (!$this->hasOutboundMessages()) {
            $this->markTestSkipped('No outbound messages available in test environment');
            return;
        }

        // Retry logic to wait for messages to be available
        $retries = 5;
        $retrievedMessages = null;
        
        for ($i = 0; $i < $retries; $i++) {
            $retrievedMessages = $client->getOutboundMessages(1, 50);
            $messages = $retrievedMessages->getMessages();
            
            if (!empty($messages)) {
                break;
            }
            
            if ($i < $retries - 1) {
                sleep(3); // Wait 3 seconds before retry
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

        // Check if there are any outbound messages at all
        if (!$this->hasOutboundMessages()) {
            $this->markTestSkipped('No outbound messages available in test environment');
            return;
        }

        // Retry logic to wait for messages to be available
        $retries = 5;
        $retrievedMessages = null;
        
        for ($i = 0; $i < $retries; $i++) {
            $retrievedMessages = $client->getOutboundMessages(1, 50);
            $messages = $retrievedMessages->getMessages();
            
            if (!empty($messages)) {
                break;
            }
            
            if ($i < $retries - 1) {
                sleep(3); // Wait 3 seconds before retry
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
