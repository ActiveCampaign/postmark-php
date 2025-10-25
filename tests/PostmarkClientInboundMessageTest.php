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
     * Set up test data by sending test messages
     */
    private function ensureTestDataExists()
    {
        if (self::$testDataCreated) {
            return;
        }
        
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        
        // Send multiple test messages to create inbound message data
        for ($i = 0; $i < 12; $i++) {
            try {
                $client->sendEmail(
                    $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
                    $tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS,
                    "Test Inbound Message $i",
                    "This is test message $i for inbound testing",
                    "This is test message $i for inbound testing"
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
    public function testClientCanSearchInboundMessages()
    {
        // Ensure test data exists
        $this->ensureTestDataExists();
        
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        // Retry logic to wait for messages to be available
        $retries = 5; // Increased retries
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
        // Ensure test data exists
        $this->ensureTestDataExists();
        
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        // Retry logic to wait for messages to be available
        $retries = 5; // Increased retries
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
