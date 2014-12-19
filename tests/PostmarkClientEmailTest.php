<?php

namespace Postmark\Tests;

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/TestingKeys.php";

use Postmark\PostmarkClient;

class PostmarkClientEmailTest extends \PHPUnit_Framework_TestCase {

	static $testKeys;

	static function setUpBeforeClass() {
		//get the config keys for the various tests
		PostmarkClientEmailTest::$testKeys = new TestingKeys();
		date_default_timezone_set("UTC");
	}

	function testClientCanSendBasicMessage() {
		$tk = PostmarkClientEmailTest::$testKeys;

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