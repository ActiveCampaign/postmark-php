<?php

namespace Postmark\Tests;

use Postmark\PostmarkClient;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

class PostmarkClientEmailTest extends PostmarkClientBaseTest {

	function testClientCanSendBasicMessage() {
		$tk = parent::$testKeys;

		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN);

		$currentTime = date("c");

		$response = $client->sendEmail($tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
			$tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS,
			"Hello from the PHP Postmark Client Tests! ($currentTime)",
			'<b>Hi there!</b>',
			'This is a text body for a test email.');
		$this->assertNotEmpty($response, 'The client could not send a basic message.');
	}
}

?>