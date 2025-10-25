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
     * Set up test data by sending test messages
     */
    private function ensureTestDataExists()
    {
        if (self::$testDataCreated) {
            return;
        }
        
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        
        // Send multiple test messages to create outbound message data
        for ($i = 0; $i < 12; $i++) {
            try {
                $client->sendEmail(
                    $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
                    $tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS,
                    "Test Outbound Message $i",
                    "This is test message $i for outbound testing",
                    "This is test message $i for outbound testing"
                );
                // Small delay between messages
                usleep(100000); // 0.1 second
            } catch (Exception $e) {
                // Continue with other messages if one fails
                continue;
            }
        }
        
        // Wait a moment for messages to be processed
        sleep(2);
        self::$testDataCreated = true;
    }
    public function testClientCanSearchOutboundMessages()
    {
        // Ensure test data exists
        $this->ensureTestDataExists();
        
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        // Retry logic to wait for messages to be available
        $retries = 5; // Increased retries
        $messages = null;
        
        for ($i = 0; $i < $retries; $i++) {
            $messages = $client->getOutboundMessages(1, 50);
            $outboundMessages = $messages->getMessages();
            
            if (count($outboundMessages) >= 10) {
                break;
            }
            
            if ($i < $retries - 1) {
                sleep(3); // Wait 3 seconds before retry
            }
        }

        $this->assertNotEmpty($messages);
        $outboundMessages = $messages->getMessages();
        $this->assertGreaterThanOrEqual(10, count($outboundMessages), 'Expected at least 10 outbound messages after retries');
    }

    public function testClientCanGetOutboundMessageDetails()
    {
        // Ensure test data exists
        $this->ensureTestDataExists();
        
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        // Retry logic to wait for messages to be available
        $retries = 5; // Increased retries
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
        // Ensure test data exists
        $this->ensureTestDataExists();
        
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        // Retry logic to wait for messages to be available
        $retries = 5; // Increased retries
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
