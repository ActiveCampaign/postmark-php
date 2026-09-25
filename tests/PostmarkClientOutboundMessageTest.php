<?php

namespace Postmark\Tests;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

use Postmark\PostmarkClient;

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkClientOutboundMessageTest extends PostmarkClientBaseTest
{
    /** @var null|array{id: string, tag: string} one seeded message, shared by the class */
    private static ?array $seed = null;

    protected function setUp(): void
    {
        parent::setUp();

        // hasAnyCredentials() in the base setUp passes when ANY ONE of six tokens is set, so a
        // partially-configured environment reaches the client constructor and fatals with
        // "Argument #1 ($serverToken) must be of type string, null given". Guard the token this
        // class actually uses so it skips with a name instead.
        $this->requireKeys('READ_SELENIUM_TEST_SERVER_TOKEN');
    }

    /** @return array{0: PostmarkClient, 1: array{id: string, tag: string}} */
    private function clientWithSeed(): array
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        self::$seed ??= $this->seedOutboundMessage($client, 'outbound');

        return [$client, self::$seed];
    }

    public function testClientCanSearchOutboundMessages()
    {
        [$client, $seed] = $this->clientWithSeed();

        $filtered = $client->getOutboundMessages(10, 0, tag: $seed['tag'])->getMessages();
        $this->assertCount(1, $filtered);
        $this->assertSame($seed['id'], $filtered[0]->getMessageID());

        $page = $client->getOutboundMessages(10)->getMessages();
        $this->assertNotEmpty($page);
        $this->assertLessThanOrEqual(10, count($page));
    }

    public function testClientCanGetOutboundMessageDetails()
    {
        [$client, $seed] = $this->clientWithSeed();

        $message = $client->getOutboundMessageDetails($seed['id']);

        $this->assertSame($seed['id'], $message->getMessageID());
        $this->assertStringContainsString('Seeded by the postmark-php integration suite.', $message->getTextBody());
    }

    public function testClientCanGetOutboundMessageDump()
    {
        [$client, $seed] = $this->clientWithSeed();

        $dump = $client->getOutboundMessageDump($seed['id']);

        $this->assertStringContainsString($seed['tag'], $dump->getBody());
    }
}
