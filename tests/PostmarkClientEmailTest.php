<?php

namespace Postmark\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Postmark\Models\PostmarkAttachment;
use Postmark\Models\PostmarkException;
use Postmark\Models\PostmarkMessage;
use Postmark\Models\PostmarkMessageBase;
use Postmark\PostmarkClient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkClientEmailTest extends PostmarkClientBaseTest
{
    public function testClientCanSendBasicMessage()
    {
        $tk = parent::$testKeys;

        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $currentTime = date('c');

        $response = $client->sendEmail(
            $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
            $tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS,
            "Hello from the PHP Postmark Client Tests! ({$currentTime})",
            '<b>Hi there!</b>',
            'This is a text body for a test email.'
        );
        $this->assertNotEmpty($response, 'The client could not send a basic message.');
    }

    public function testClientCanSetMessageStream()
    {
        $tk = parent::$testKeys;

        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $currentTime = date('c');

        // Sending with a valid stream
        $response = $client->sendEmail(
            $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
            $tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS,
            "Hello from the PHP Postmark Client Tests! ({$currentTime})",
            '<b>Hi there!</b>',
            'This is a text body for a test email via the default stream.',
            null,
            true,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            'outbound'
        );
        $this->assertNotEmpty($response, 'The client could not send message to the default stream.');

        // Sending with an invalid stream
        try {
            $response = $client->sendEmail(
                $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
                $tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS,
                "Hello from the PHP Postmark Client Tests! ({$currentTime})",
                '<b>Hi there!</b>',
                'This is a text body for a test email.',
                null,
                true,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                'unknown-stream'
            );
        } catch (PostmarkException $ex) {
            $this->assertEquals(422, $ex->getHttpStatusCode());
            $this->assertEquals("The stream provided: 'unknown-stream' does not exist on this server.", $ex->getMessage());
        }
    }

    public function testClientSendModel()
    {
        $tk = parent::$testKeys;

        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $currentTime = date('c');

        $emailModel = new PostmarkMessage();
        $emailModel->setFrom($tk->WRITE_TEST_SENDER_EMAIL_ADDRESS);
        $emailModel->setTo($tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS);
        $emailModel->setSubject("Hello from the PHP Postmark Client Tests! ({$currentTime})");
        $emailModel->setHtmlBody('<b>Hi there!</b>');
        $emailModel->setTextBody('This is a text body for a test email.');
        $emailModel->setMessageStream('outbound');

        $response = $client->sendEmailModel($emailModel);

        $this->assertNotEmpty($response, 'The client could not send message to the default stream.');
    }

    public function testClientCanSendMessageWithRawAttachment()
    {
        $tk = parent::$testKeys;

        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $currentTime = date('c');

        $attachment = PostmarkAttachment::fromRawData(
            'attachment content',
            'hello.txt',
            'text/plain'
        );

        $response = $client->sendEmail(
            $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
            $tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS,
            "Hello from the PHP Postmark Client Tests! ({$currentTime})",
            '<b>Hi there!</b>',
            'This is a text body for a test email.',
            null,
            true,
            null,
            null,
            null,
            ['X-Test-Header' => 'Header.', 'X-Test-Header-2' => 'Test Header 2'],
            [$attachment]
        );

        $this->assertNotEmpty($response, 'The client could not send a message with an attachment.');
    }

    public function testClientCanSendMessageWithFileSystemAttachment()
    {
        $tk = parent::$testKeys;

        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $currentTime = date('c');

        $attachment = PostmarkAttachment::fromFile(
            dirname(__FILE__) . '/postmark-logo.png',
            'hello.png',
            'image/png'
        );

        $response = $client->sendEmail(
            $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
            $tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS,
            "Hello from the PHP Postmark Client Tests! ({$currentTime})",
            '<b>Hi there! From <img src="cid:hello.png"/></b>',
            'This is a text body for a test email.',
            null,
            true,
            null,
            null,
            null,
            ['X-Test-Header' => 'Header.', 'X-Test-Header-2' => 'Test Header 2'],
            [$attachment]
        );

        $this->assertNotEmpty($response, 'The client could not send a message with an attachment.');
    }

    public function testClientCanSendBatchMessages()
    {
        $tk = parent::$testKeys;

        $currentTime = date('c');

        $batch = [];

        $attachment = PostmarkAttachment::fromRawData(
            'attachment content',
            'hello.txt',
            'text/plain'
        );

        for ($i = 0; $i < 5; ++$i) {
            $payload = [
                'From' => $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
                'To' => $tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS,
                'Subject' => "Hello from the PHP Postmark Client Tests! ({$currentTime})",
                'HtmlBody' => '<b>Hi there! (batch test)</b>',
                'TextBody' => 'This is a text body for a test email.',
                'TrackOpens' => true,
                'Headers' => ['X-Test-Header' => 'Test Header Content', 'X-Test-Date-Sent' => date('c')],
                'Attachments' => [$attachment],
            ];

            $batch[] = $payload;
        }

        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        $response = $client->sendEmailBatch($batch);
        $this->assertNotEmpty($response, 'The client could not send a batch of messages.');
    }

    public function testRequestSentWithCustomGuzzleClientHasCorrectUri()
    {
        $successResponse = new Response(
            200,
            ['content-type' => 'application/json'],
            json_encode([
                'To' => 'user@example.com',
                'SubmittedAt' => '2014-02-17T07:25:01.4178645-05:00',
                'MessageId' => '0a129aee-e1cd-480d-b08d-4f48548ff48d',
                'ErrorCode' => 0,
                'Message' => 'OK',
            ])
        );

        $guzzleMockHandler = new MockHandler();
        $guzzleMockHandler->append($successResponse);

        $httpHistoryContainer = [];

        $handlerStack = HandlerStack::create($guzzleMockHandler);
        $handlerStack->push(Middleware::history($httpHistoryContainer), 'history');

        $guzzleClient = new Client([
            'handler' => $handlerStack,
        ]);
        $postmarkClient = new PostmarkClient('not-applicable');

        $postmarkClient->setClient($guzzleClient);

        $postmarkClient->sendEmail(
            'sender@example.com',
            'recipient@example.com',
            'Test message',
            null,
            'Text body'
        );

        // @var RequestInterface $lastRequest
        $lastRequest = $httpHistoryContainer[0]['request'];

        // @var UriInterface $lastRequestUri
        $lastRequestUri = $lastRequest->getUri();

        $this->assertEquals(
            PostmarkClient::$BASE_URL,
            sprintf('%s://%s', $lastRequestUri->getScheme(), $lastRequestUri->getHost())
        );
    }
}
