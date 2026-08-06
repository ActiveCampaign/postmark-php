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
    /**
     * Archive a stream, tolerating the API's post-creation cooldown.
     *
     * A stream created moments earlier is refused with "Stream is unable to be
     * archived at this time." That is an API state condition, not an SDK fault, so
     * retry briefly and then skip with the API's own message rather than reporting
     * it as a client failure.
     */
    private function archiveOrSkip(PostmarkClient $client, string $streamId): \Postmark\Models\MessageStream\PostmarkMessageStreamArchivalConfirmation
    {
        $lastMessage = '';

        for ($attempt = 0; $attempt < 3; ++$attempt) {
            try {
                return $client->archiveMessageStream($streamId);
            } catch (PostmarkException $e) {
                $lastMessage = $e->getMessage();

                if (false === stripos($lastMessage, 'unable to be archived')) {
                    throw $e;
                }

                sleep(2);
            }
        }

        $this->markTestSkipped('Postmark refused to archive the stream: ' . $lastMessage);
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
        $tk = parent::$testKeys;
        $server = self::getNewServer();
        $client = new PostmarkClient($server->ApiTokens[0], $tk->TEST_TIMEOUT);

        $newStream = $client->createMessageStream('test-stream', 'Broadcasts', 'Test Stream Name');

        // 2 broadcast streams, including the default one
        $this->assertEquals(2, $client->listMessageStreams('Broadcasts')->getTotalCount());

        $this->archiveOrSkip($client, $newStream->getID());

        // Filtering out archived streams by default
        $this->assertEquals(1, $client->listMessageStreams('Broadcasts')->getTotalCount());

        // Allowing archived streams in the result
        $this->assertEquals(2, $client->listMessageStreams('Broadcasts', 'true')->getTotalCount());
    }

    // archive message streams
    public function testClientCanArchiveStreams()
    {
        $tk = parent::$testKeys;
        $server = self::getNewServer();
        $client = new PostmarkClient($server->ApiTokens[0], $tk->TEST_TIMEOUT);

        $newStream = $client->createMessageStream('test-stream', 'Broadcasts', 'Test Stream Name');
        $archivedStream = $this->archiveOrSkip($client, $newStream->getID());

        $this->assertEquals($newStream->getID(), $archivedStream->getID());
        $this->assertEquals($newStream->getServerId(), $archivedStream->getServerId());
        $this->assertNotNull($archivedStream->getExpectedPurgeDate());

        $fetchedStream = $client->getMessageStream($archivedStream->getID());
        $this->assertNotNull($fetchedStream->getArchivedAt());
    }

    // unarchive message streams
    public function testClientCanUnarchiveStreams()
    {
        $tk = parent::$testKeys;
        $server = self::getNewServer();
        $client = new PostmarkClient($server->ApiTokens[0], $tk->TEST_TIMEOUT);

        $newStream = $client->createMessageStream('test-stream', 'Broadcasts', 'Test Stream Name');
        $this->archiveOrSkip($client, $newStream->getID());

        $unarchivedStream = $client->unArchiveMessageStream($newStream->getID());

        $this->assertNull($unarchivedStream->getArchivedAt());
    }

    private static function getNewServer()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        return $client->createServer('test-php-streams-' . uniqid());
    }
}
