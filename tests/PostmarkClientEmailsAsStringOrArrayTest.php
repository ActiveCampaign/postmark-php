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
        // The point of this file is many-recipients-as-an-array, so the fixture has to
        // stay multi-recipient. The uniqid suffix is what avoids suppression collisions
        // between runs; collapsing to a single address would remove the coverage instead.
        $run = uniqid();
        $emailsAsArray = [];
        for ($i = 1; $i <= 50; ++$i) {
            $emailsAsArray[] = str_replace('@', '+' . $run . $i . '@', $tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS);
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
        // As above: the comma-delimited string is the thing under test, so it stays.
        $run = uniqid();
        $emails = [];
        for ($i = 1; $i <= 50; ++$i) {
            $emails[] = str_replace('@', '+' . $run . $i . '@', $tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS);
        }
        $emailsAsString = implode(',', $emails);

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
