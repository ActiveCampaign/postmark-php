<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use Postmark\PostmarkAdminClient as PostmarkAdminClient;

class PostmarkAdminClientServersTest extends PostmarkClientBaseTest {

	static function tearDownAfterClass() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$servers = $client->listServers();

		foreach ($servers->servers as $key => $value) {
			if (preg_match('/^test-php.+/', $value->name) > 0) {
				$client->deleteServer($value->id);
			}
		}
	}

	function testClientCanGetServerList() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$servers = $client->listServers();

		$this->assertGreaterThan(0, $servers['totalcount']);
		$this->assertNotEmpty($servers->servers[0]);
	}

	function testClientCanGetSingleServer() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$servers = $client->listServers();
		$targetId = $servers->servers[0]->id;

		$server = $client->getServer($targetId);

		$this->assertNotEmpty($server->name);
	}

	function testClientCanCreateServer() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$server = $client->createServer('test-php-create-' . date('c'));
		$this->assertNotEmpty($server);
	}

	function testClientCanEditServer() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$server = $client->createServer('test-php-edit-' . date('c'), 'purple');

		$updateName = 'test-php-edit-' . date('c') . '-updated';
		$serverUpdated = $client->editServer($server->id, $updateName, 'green');

		$this->assertNotEmpty($serverUpdated);
		$this->assertNotSame($serverUpdated->name, $server->name);
		$this->assertNotSame($serverUpdated->color, $server->color);
	}

	function testClientCanDeleteServer() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$server = $client->createServer('test-php-delete-' . date('c'));

		$client->deleteServer($server->id);

		$serverList = $client->listServers();
		foreach ($serverList->servers as $key => $value) {
			$this->assertNotSame($value->id, $server->id);
		}
	}
}

?>