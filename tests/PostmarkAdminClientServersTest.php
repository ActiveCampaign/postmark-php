<?php

namespace Postmark\Tests;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

use Postmark\PostmarkAdminClient;

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkAdminClientServersTest extends PostmarkClientBaseTest
{
    public static function tearDownAfterClass(): void
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $servers = $client->listServers();

        foreach ($servers->getServers() as $key => $value) {
            if (preg_match('/^test-php.+/', $value->getName()) > 0 && !empty($value->getID())) {
                $client->deleteServer($value->getID());
            }
        }
    }

    public function testClientCanGetServerList()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $servers = $client->listServers();

        $this->assertGreaterThan(0, $servers->getTotalcount());
        $this->assertNotEmpty($servers->getServers()[0]);
    }

    public function testClientCanGetSingleServer()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $servers = $client->listServers();
        $targetId = $servers->getServers()[0]->getID();

        $server = $client->getServer($targetId);

        $this->assertNotEmpty($server->getName());
    }

    public function testClientCanCreateServer()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $server = $client->createServer('test-php-create-' . date('c'));
        $this->assertNotEmpty($server);
    }

    public function testClientCanEditServer()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $server = $client->createServer('test-php-edit-' . date('c'), 'purple');

        $updateName = 'test-php-edit-' . date('c') . '-updated';
        $serverUpdated = $client->editServer($server->getID(), $updateName, 'green');

        $this->assertNotEmpty($serverUpdated);
        $this->assertNotSame($serverUpdated->getName(), $server->getName());
        $this->assertNotSame($serverUpdated->getColor(), $server->getColor());
    }

    public function testClientCanDeleteServer()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $server = $client->createServer('test-php-delete-' . date('c'));

        $client->deleteServer($server->getID());

        $serverList = $client->listServers();
        foreach ($serverList->getServers() as $key => $value) {
            $this->assertNotSame($value->getID(), $server->getID());
        }
    }
}
