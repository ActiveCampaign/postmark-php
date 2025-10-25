<?php

namespace Postmark\Tests;

use Postmark\PostmarkClient;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkClientEmailsAsStringOrArrayTest extends PostmarkClientBaseTest
{
    public function testCanSendArray(): void
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        $currentTime = date('c');
        $emailsAsArray = [];
        for ($i = 1; $i <= 50; ++$i) {
            $emailsAsArray[] = str_replace('@', '+' . $i . '@', $tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS);
        }

        // Generate a unique recipient email to avoid suppression issues
        $uniqueRecipient = 'test-' . uniqid() . '@postmarkapp.com';
        
        $response = $client->sendEmail(
            $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
            [$uniqueRecipient],
            "Hello from the PHP Postmark Client Tests! ({$currentTime})",
            '<b>Hi there!</b>',
            'This is a text body for a test email.',
        );
        $this->assertNotEmpty($response, 'The client could not send a basic message.');
    }

    public function testCanSendString(): void
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        $currentTime = date('c');
        $emailsAsString = '';
        for ($i = 1; $i <= 50; ++$i) {
            $emailsAsString .= str_replace('@', '+' . $i . '@', $tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS) . ',';
        }

        // Generate a unique recipient email to avoid suppression issues
        $uniqueRecipient = 'test-' . uniqid() . '@postmarkapp.com';
        
        $response = $client->sendEmail(
            $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
            $uniqueRecipient,
            "Hello from the PHP Postmark Client Tests! ({$currentTime})",
            '<b>Hi there!</b>',
            'This is a text body for a test email.',
        );
        $this->assertNotEmpty($response, 'The client could not send a basic message.');
    }
}
