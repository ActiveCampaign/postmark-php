<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use \Postmark\PostmarkClient;

class PostmarkClientServerTest extends PostmarkClientBaseTest {

	function testClientCanGetServerInformation() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
		$server = $client->getServer();
		$this->assertNotEmpty($server);
	}

	function testClientCanEditServerInformation() {
		$tk = parent::$testKeys;

		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
		$originalServer = $client->getServer();

		$server = $client->editServer('testing-server-' . date('c'));

		//set it back to the original name.
		$client->editServer($originalServer->Name);
		$this->assertNotSame($originalServer->Name, $server->Name);
	}
}
?>