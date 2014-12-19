<?php

namespace Postmark\Tests;

class TestingKeys {

	public $READ_INBOUND_TEST_SERVER_TOKEN;
	public $READ_SELENIUM_TEST_SERVER_TOKEN;

	public $WRITE_ACCOUNT_TOKEN;
	public $WRITE_TEST_SERVER_TOKEN;
	public $WRITE_TEST_SENDER_EMAIL_ADDRESS;
	public $WRITE_TEST_EMAIL_RECIPIENT_ADDRESS;

	function __construct() {
		$test_keys = [];

		$keyfile = __DIR__ . "/../test_keys.json";

		if (file_exists($keyfile)) {
			$keys = file_get_contents($keyfile);
			$test_keys = json_decode($keys, true);
		}

		$this->READ_INBOUND_TEST_SERVER_TOKEN = getenv("READ_INBOUND_TEST_SERVER_TOKEN") ?: $test_keys["READ_INBOUND_TEST_SERVER_TOKEN"];
		$this->READ_SELENIUM_TEST_SERVER_TOKEN = getenv("READ_SELENIUM_TEST_SERVER_TOKEN") ?: $test_keys["READ_SELENIUM_TEST_SERVER_TOKEN"];

		$this->WRITE_ACCOUNT_TOKEN = getenv("WRITE_ACCOUNT_TOKEN") ?: $test_keys["WRITE_ACCOUNT_TOKEN"];
		$this->WRITE_TEST_SERVER_TOKEN = getenv("WRITE_TEST_SERVER_TOKEN") ?: $test_keys["WRITE_TEST_SERVER_TOKEN"];
		$this->WRITE_TEST_SENDER_EMAIL_ADDRESS = getenv("WRITE_TEST_SENDER_EMAIL_ADDRESS") ?: $test_keys["WRITE_TEST_SENDER_EMAIL_ADDRESS"];
		$this->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS = getenv("WRITE_TEST_EMAIL_RECIPIENT_ADDRESS") ?: $test_keys["WRITE_TEST_EMAIL_RECIPIENT_ADDRESS"];
	}
}

?>