<?php

namespace Postmark\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;
use Postmark\Models\PostmarkAttachment;
use Postmark\Models\PostmarkException;
use Postmark\PostmarkClient;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

class PostmarkClientEmailTest extends PostmarkClientBaseTest {

	function testClientCanSendBasicMessage() {
		$tk = parent::$testKeys;

		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$currentTime = date("c");

		$response = $client->sendEmail($tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
			$tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS,
			"Hello from the PHP Postmark Client Tests! ($currentTime)",
			'<b>Hi there!</b>',
			'This is a text body for a test email.');
		$this->assertNotEmpty($response, 'The client could not send a basic message.');
	}

	function testClientCanSetMessageStream() {
		$tk = parent::$testKeys;

		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$currentTime = date("c");
		
		//Sending with a valid stream
		$response = $client->sendEmail($tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
			$tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS,
			"Hello from the PHP Postmark Client Tests! ($currentTime)",
			'<b>Hi there!</b>',
			'This is a text body for a test email via the default stream.', NULL, true, NULL, NULL, NULL,
			NULL, NULL, NULL, NULL, 'outbound');
		$this->assertNotEmpty($response, 'The client could not send message to the default stream.');
		
		// Sending with an invalid stream
		try {
			$response = $client->sendEmail($tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
				$tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS,
				"Hello from the PHP Postmark Client Tests! ($currentTime)",
				'<b>Hi there!</b>',
				'This is a text body for a test email.', NULL, true, NULL, NULL, NULL,
				NULL, NULL, NULL, NULL, 'unknown-stream');
		} catch(PostmarkException $ex){
			$this->assertEquals(422, $ex->httpStatusCode);
			$this->assertEquals("The 'MessageStream' provided does not exist on this server.", $ex->message);
		}
	}

	function testClientCanSendMessageWithRawAttachment() {
		$tk = parent::$testKeys;

		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$currentTime = date("c");

		$attachment = PostmarkAttachment::fromRawData("attachment content",
			"hello.txt", "text/plain");

		$response = $client->sendEmail($tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
			$tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS,
			"Hello from the PHP Postmark Client Tests! ($currentTime)",
			'<b>Hi there!</b>',
			'This is a text body for a test email.',
			NULL, true, NULL, NULL, NULL,
			array("X-Test-Header" => "Header.", 'X-Test-Header-2' => 'Test Header 2'), array($attachment));

		$this->assertNotEmpty($response, 'The client could not send a message with an attachment.');
	}

	function testClientCanSendMessageWithFileSystemAttachment() {
		$tk = parent::$testKeys;

		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$currentTime = date("c");

		$attachment = PostmarkAttachment::fromFile(dirname(__FILE__) . '/postmark-logo.png',
			"hello.png", "image/png");

		$response = $client->sendEmail($tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
			$tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS,
			"Hello from the PHP Postmark Client Tests! ($currentTime)",
			'<b>Hi there! From <img src="cid:hello.png"/></b>',
			'This is a text body for a test email.',
			NULL, true, NULL, NULL, NULL,
			array("X-Test-Header" => "Header.", 'X-Test-Header-2' => 'Test Header 2'), array($attachment));

		$this->assertNotEmpty($response, 'The client could not send a message with an attachment.');
	}

	function testClientCanSendBatchMessages() {
		$tk = parent::$testKeys;

		$currentTime = date("c");

		$batch = array();

		$attachment = PostmarkAttachment::fromRawData("attachment content",
			"hello.txt", "text/plain");

		for ($i = 0; $i < 5; $i++) {
			$payload = array(
				'From' => $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
				'To' => $tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS,
				'Subject' => "Hello from the PHP Postmark Client Tests! ($currentTime)",
				'HtmlBody' => '<b>Hi there! (batch test)</b>',
				'TextBody' => 'This is a text body for a test email.',
				'TrackOpens' => true,
				'Headers' => array("X-Test-Header" => "Test Header Content", 'X-Test-Date-Sent' => date('c')),
				'Attachments' => array($attachment),
            );

			$batch[] = $payload;
		}

		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
		$response = $client->sendEmailBatch($batch);
		$this->assertNotEmpty($response, 'The client could not send a batch of messages.');
	}

	public function testRequestSentWithCustomGuzzleClientHasCorrectUri() {
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

        /* @var RequestInterface $lastRequest */
        $lastRequest = $httpHistoryContainer[0]['request'];

        /* @var UriInterface $lastRequestUri */
        $lastRequestUri = $lastRequest->getUri();

        $this->assertEquals(
            PostmarkClient::$BASE_URL,
            sprintf('%s://%s', $lastRequestUri->getScheme(), $lastRequestUri->getHost())
        );
    }
}

?>