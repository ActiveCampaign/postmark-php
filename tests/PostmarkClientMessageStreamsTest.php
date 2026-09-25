<?php

namespace Postmark\Tests;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

use Postmark\PostmarkAdminClient;
use Postmark\Models\PostmarkException;
use Postmark\PostmarkClient;

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkClientMessageStreamsTest extends PostmarkClientBaseTest
{
    protected function setUp(): void
    {
        parent::setUp();

        // hasAnyCredentials() in the base setUp passes when ANY ONE of six tokens is set, so a
        // partially-configured environment reaches the client constructor and fatals with
        // "Argument #1 ($serverToken) must be of type string, null given". Guard the token this
        // class actually uses so it skips with a name instead.
        $this->requireKeys('WRITE_ACCOUNT_TOKEN');
    }

    public static function tearDownAfterClass(): void
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $servers = $client->listServers();

        foreach ($servers->getServers() as $key => $value) {
            if (preg_match('/^test-php-streams.+/', $value->getName()) > 0 && !empty($value->getID())) {
                $client->deleteServer($value->getID());
            }
        }
    }

    // create message stream

    private const ARCHIVE_FIXTURE = 'php-sdk-ci-archive';

    /**
     * A client for the shared test server and its long-lived archive fixture, unarchived and ready.
     *
     * Postmark refuses to archive a stream younger than 48 hours, or one that sent mail today or
     * yesterday — account-management's anti-abuse guardrail, reported only as error 1241 "Stream is
     * unable to be archived at this time". A stream the test just created can therefore never be
     * archived. This one lives on the shared test server, never sends, and is archived and
     * unarchived in place. If it has gone missing it is recreated and the tests skip until it is old
     * enough — once, not on every run.
     */
    private function archiveFixtureClient(): PostmarkClient
    {
        $this->requireKeys('WRITE_TEST_SERVER_TOKEN');
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        try {
            $stream = $client->getMessageStream(self::ARCHIVE_FIXTURE);
        } catch (PostmarkException $e) {
            if (404 !== $e->getHttpStatusCode()) {
                throw $e;
            }

            $stream = $client->createMessageStream(
                self::ARCHIVE_FIXTURE,
                'Broadcasts',
                'PHP SDK CI archive fixture - do not delete',
                'Archived and unarchived in place by the postmark-php integration suite. Must never send.'
            );
        }

        if (null !== $stream->getArchivedAt()) {
            // A run that died between archive and unarchive leaves it archived; start from clean.
            $client->unarchiveMessageStream(self::ARCHIVE_FIXTURE);
        }

        $archivableAt = (new \DateTimeImmutable($stream->getCreatedAt()))->modify('+48 hours');

        if ($archivableAt > new \DateTimeImmutable()) {
            $this->markTestSkipped(sprintf(
                'Archive fixture %s is younger than 48 hours; Postmark refuses to archive it until %s.',
                self::ARCHIVE_FIXTURE,
                $archivableAt->format(DATE_ATOM)
            ));
        }

        return $client;
    }

    private function archiveFixture(PostmarkClient $client): \Postmark\Models\MessageStream\PostmarkMessageStreamArchivalConfirmation
    {
        try {
            return $client->archiveMessageStream(self::ARCHIVE_FIXTURE);
        } catch (PostmarkException $e) {
            if (false === stripos($e->getMessage(), 'unable to be archived')) {
                throw $e;
            }

            $this->fail(
                'Postmark refused to archive ' . self::ARCHIVE_FIXTURE . ', which is over 48 hours old. '
                . 'The remaining guardrails are a send from this stream today or yesterday, or a '
                . 'suspended stream — both mean the fixture was misused, not an SDK fault. API said: '
                . $e->getMessage()
            );
        }
    }

    /** @return string[] */
    private static function streamIds(PostmarkClient $client, string $includeArchived): array
    {
        return array_map(
            static fn ($s) => $s->getID(),
            $client->listMessageStreams('Broadcasts', $includeArchived)->getMessageStreams()
        );
    }

    public function testClientCanCreateMessageStream()
    {
        $tk = parent::$testKeys;
        $server = self::getNewServer();
        $client = new PostmarkClient($server->ApiTokens[0], $tk->TEST_TIMEOUT);

        $id = 'test-stream';
        $messageStreamType = 'Broadcasts';
        $name = 'Test Stream Name';
        $description = 'Test Stream Description';

        $createdStream = $client->createMessageStream($id, $messageStreamType, $name, $description);

        $this->assertEquals($id, $createdStream->getID());
        $this->assertEquals($server->getID(), $createdStream->getServerId());
        $this->assertEquals($messageStreamType, $createdStream->getMessageStreamType());
        $this->assertEquals($name, $createdStream->getName());
        $this->assertEquals($description, $createdStream->getDescription());
        $this->assertNotNull($createdStream->getCreatedAt());
        $this->assertNull($createdStream->getUpdatedAt());
        $this->assertNull($createdStream->getArchivedAt());
    }

    // edit message stream
    public function testClientCanEditMessageStream()
    {
        $tk = parent::$testKeys;
        $server = self::getNewServer();
        $client = new PostmarkClient($server->ApiTokens[0], $tk->TEST_TIMEOUT);

        $id = 'test-stream';
        $messageStreamType = 'Broadcasts';
        $name = 'Test Stream Name';
        $description = 'Test Stream Description';

        $client->createMessageStream($id, $messageStreamType, $name, $description);

        $updatedName = 'New Name';
        $updatedDescription = 'New Description';

        $updatedStream = $client->editMessageStream($id, $updatedName, $updatedDescription);

        $this->assertEquals($id, $updatedStream->getID());
        $this->assertEquals($updatedName, $updatedStream->getName());
        $this->assertEquals($updatedDescription, $updatedStream->getDescription());
        $this->assertNotNull($updatedStream->getUpdatedAt());
    }

    // get message stream
    public function testClientCanGetMessageStream()
    {
        $tk = parent::$testKeys;
        $server = self::getNewServer();
        $client = new PostmarkClient($server->ApiTokens[0], $tk->TEST_TIMEOUT);

        $id = 'test-stream';
        $messageStreamType = 'Broadcasts';
        $name = 'Test Stream Name';
        $description = 'Test Stream Description';

        $client->createMessageStream($id, $messageStreamType, $name, $description);

        $fetchedStream = $client->getMessageStream($id);

        $this->assertEquals($id, $fetchedStream->getID());
        $this->assertEquals($messageStreamType, $fetchedStream->getMessageStreamType());
        $this->assertEquals($name, $fetchedStream->getName());
        $this->assertEquals($description, $fetchedStream->getDescription());
    }

    // list message streams
    public function testClientCanListMessageStreams()
    {
        $tk = parent::$testKeys;
        $server = self::getNewServer();
        $client = new PostmarkClient($server->ApiTokens[0], $tk->TEST_TIMEOUT);

        $broadcastsStream = $client->createMessageStream('test-stream', 'Broadcasts', 'Test Stream Name');

        $this->assertEquals(4, $client->listMessageStreams()->getTotalCount()); // Includes 3 default streams

        $filteredStreams = $client->listMessageStreams('Broadcasts');

        $this->assertEquals(2, $filteredStreams->getTotalCount()); // Filter only our Broadcasts streams
    }

    // list archived message streams
    public function testClientCanListArchivedStreams()
    {
        $client = $this->archiveFixtureClient();

        try {
            $this->archiveFixture($client);

            $this->assertNotContains(self::ARCHIVE_FIXTURE, self::streamIds($client, 'false'), 'archived streams are filtered out by default');
            $this->assertContains(self::ARCHIVE_FIXTURE, self::streamIds($client, 'true'));
        } finally {
            $client->unarchiveMessageStream(self::ARCHIVE_FIXTURE);
        }
    }

    // archive message streams
    public function testClientCanArchiveStreams()
    {
        $client = $this->archiveFixtureClient();

        try {
            $archived = $this->archiveFixture($client);

            $this->assertEquals(self::ARCHIVE_FIXTURE, $archived->getID());
            $this->assertNotEmpty($archived->getExpectedPurgeDate());
            $this->assertNotNull($client->getMessageStream(self::ARCHIVE_FIXTURE)->getArchivedAt());
        } finally {
            $client->unarchiveMessageStream(self::ARCHIVE_FIXTURE);
        }
    }

    // unarchive message streams
    public function testClientCanUnarchiveStreams()
    {
        $client = $this->archiveFixtureClient();
        $this->archiveFixture($client);

        $unarchived = $client->unarchiveMessageStream(self::ARCHIVE_FIXTURE);

        $this->assertNull($unarchived->getArchivedAt());
        $this->assertNull($client->getMessageStream(self::ARCHIVE_FIXTURE)->getArchivedAt());
    }

    private static function getNewServer()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        return $client->createServer('test-php-streams-' . uniqid());
    }
}
