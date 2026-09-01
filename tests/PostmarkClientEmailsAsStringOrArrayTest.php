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
    protected function setUp(): void
    {
        parent::setUp();

        // hasAnyCredentials() in the base setUp is true when ANY ONE of six tokens is set, so a
        // partially-configured environment passes it. Guard the token this class actually constructs
        // clients with, or the skip never fires and PostmarkClient's constructor throws
        // "Argument #1 ($serverToken) must be of type string, null given" — the exact fatal this
        // release exists to remove.
        $this->requireKeys('WRITE_TEST_SERVER_TOKEN');
        $this->requireConfirmedSenderSignature();
    }

    public function testCanSendArray(): void
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        $currentTime = date('c');
        $emailsAsArray = [];
        for ($i = 1; $i <= 50; ++$i) {
            $emailsAsArray[] = str_replace('@', '+' . $i . '@', $tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS);
        }

        $response = $client->sendEmail(
            $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
            $emailsAsArray,
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

        $response = $client->sendEmail(
            $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
            $emailsAsString,
            "Hello from the PHP Postmark Client Tests! ({$currentTime})",
            '<b>Hi there!</b>',
            'This is a text body for a test email.',
        );
        $this->assertNotEmpty($response, 'The client could not send a basic message.');
    }
}
