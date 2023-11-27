<?php

namespace Postmark\Tests;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

use Postmark\PostmarkClient;

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkClientServerTest extends PostmarkClientBaseTest
{
    public function testClientCanGetServerInformation()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        $server = $client->getServer();
        $this->assertNotEmpty($server);
    }

    public function testClientCanEditServerInformation()
    {
        $tk = parent::$testKeys;

        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        $originalServer = $client->getServer();

        $server = $client->editServer('testing-server-' . rand(0, 1000) . '-' . date('c'));

        // set it back to the original name.
        $client->editServer($originalServer->getName());
        $this->assertNotSame($originalServer->getName(), $server->getName());
    }
}
