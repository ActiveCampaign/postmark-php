<?php

namespace Postmark\Tests;

use Postmark\Models\PostmarkAttachment;
use Postmark\PostmarkClient;

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
			["X-Test-Header" => "Header.", 'X-Test-Header-2' => 'Test Header 2'], [$attachment]);

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
			["X-Test-Header" => "Header.", 'X-Test-Header-2' => 'Test Header 2'], [$attachment]);

		$this->assertNotEmpty($response, 'The client could not send a message with an attachment.');
	}

	function testClientCanSendBatchMessages() {
		$tk = parent::$testKeys;

		$currentTime = date("c");

		$batch = [];

		$attachment = PostmarkAttachment::fromRawData("attachment content",
			"hello.txt", "text/plain");

		for ($i = 0; $i < 5; $i++) {
			$payload = [
				'From' => $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
				'To' => $tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS,
				'Subject' => "Hello from the PHP Postmark Client Tests! ($currentTime)",
				'HtmlBody' => '<b>Hi there! (batch test)</b>',
				'TextBody' => 'This is a text body for a test email.',
				'TrackOpens' => true,
				'Headers' => ["X-Test-Header" => "Test Header Content", 'X-Test-Date-Sent' => date('c')],
				'Attachments' => [$attachment],
			];

			$batch[] = $payload;
		}

		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
		$response = $client->sendEmailBatch($batch);
		$this->assertNotEmpty($response, 'The client could not send a batch of messages.');
	}

}

?>