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
    protected function setUp(): void
    {
        parent::setUp();

        // hasAnyCredentials() in the base setUp passes when ANY ONE of six tokens is set, so a
        // partially-configured environment reaches the client constructor and fatals with
        // "Argument #1 ($serverToken) must be of type string, null given". Guard the token this
        // class actually uses so it skips with a name instead.
        $this->requireKeys('WRITE_TEST_SERVER_TOKEN');
    }

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
