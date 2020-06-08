<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use Postmark\PostmarkClient as PostmarkClient;
use Postmark\PostmarkAdminClient as PostmarkAdminClient;

class PostmarkClientMessageStreamsTest extends PostmarkClientBaseTest {

    public static function tearDownAfterClass() {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $servers = $client->listServers();

        foreach ($servers->servers as $key => $value) {
            if (preg_match('/^test-php-streams.+/', $value->name) > 0) {
                $client->deleteServer($value->id);
            }
        }
    }

    //create message stream
    public function testClientCanCreateMessageStream() {
        $tk = parent::$testKeys;
        $server = self::getNewServer();
        $client = new PostmarkClient($server->ApiTokens[0], $tk->TEST_TIMEOUT);

        $id = "test-stream";
        $messageStreamType = "Broadcasts";
        $name = "Test Stream Name";
        $description = "Test Stream Description";

        $createdStream = $client->createMessageStream($id, $messageStreamType, $name, $description);

        $this->assertEquals($id, $createdStream->Id);
        $this->assertEquals($server->id, $createdStream->ServerId);
        $this->assertEquals($messageStreamType, $createdStream->MessageStreamType);
        $this->assertEquals($name, $createdStream->Name);
        $this->assertEquals($description, $createdStream->Description);
        $this->assertNotNull($createdStream->CreatedAt);
        $this->assertNull($createdStream->UpdatedAt);
        $this->assertNull($createdStream->ArchivedAt);
    }

    //edit message stream
    public function testClientCanEditMessageStream() {
        $tk = parent::$testKeys;
        $server = self::getNewServer();
        $client = new PostmarkClient($server->ApiTokens[0], $tk->TEST_TIMEOUT);

        $id = "test-stream";
        $messageStreamType = "Broadcasts";
        $name = "Test Stream Name";
        $description = "Test Stream Description";

        $client->createMessageStream($id, $messageStreamType, $name, $description);

        $updatedName = "New Name";
        $updatedDescription = "New Description";

        $updatedStream = $client->editMessageStream($id, $updatedName, $updatedDescription);

        $this->assertEquals($id, $updatedStream->Id);
        $this->assertEquals($updatedName, $updatedStream->Name);
        $this->assertEquals($updatedDescription, $updatedStream->Description);
        $this->assertNotNull($updatedStream->UpdatedAt);
    }

    //get message stream
    public function testClientCanGetMessageStream() {
        $tk = parent::$testKeys;
        $server = self::getNewServer();
        $client = new PostmarkClient($server->ApiTokens[0], $tk->TEST_TIMEOUT);

        $id = "test-stream";
        $messageStreamType = "Broadcasts";
        $name = "Test Stream Name";
        $description = "Test Stream Description";

        $client->createMessageStream($id, $messageStreamType, $name, $description);

        $fetchedStream = $client->getMessageStream($id);

        $this->assertEquals($id, $fetchedStream->Id);
        $this->assertEquals($messageStreamType, $fetchedStream->MessageStreamType);
        $this->assertEquals($name, $fetchedStream->Name);
        $this->assertEquals($description, $fetchedStream->Description);
    }

    //list message streams
    public function testClientCanListMessageStreams() {
        $tk = parent::$testKeys;
        $server = self::getNewServer();
        $client = new PostmarkClient($server->ApiTokens[0], $tk->TEST_TIMEOUT);

        $broadcastsStream = $client->createMessageStream("test-stream", "Broadcasts", "Test Stream Name");

        $this->assertEquals(3, $client->listMessageStreams()->TotalCount); // Includes default streams

        $filteredStreams = $client->listMessageStreams("Broadcasts");

        $this->assertEquals(1, $filteredStreams->TotalCount); // Filter only our Broadcasts stream
    }

    //list archived message streams
    public function testClientCanListArchivedStreams() {
        $tk = parent::$testKeys;
        $server = self::getNewServer();
        $client = new PostmarkClient($server->ApiTokens[0], $tk->TEST_TIMEOUT);

        $newStream = $client->createMessageStream("test-stream", "Broadcasts", "Test Stream Name");
        $client->archiveMessageStream($newStream->Id);

        // Filtering out archived streams by default
        $this->assertEquals(0, $client->listMessageStreams("Broadcasts")->TotalCount);

        // Allowing archived streams in the result
        $this->assertEquals(1, $client->listMessageStreams("Broadcasts", "true")->TotalCount);
    }

    //archive message streams
    public function testClientCanArchiveStreams() {
        $tk = parent::$testKeys;
        $server = self::getNewServer();
        $client = new PostmarkClient($server->ApiTokens[0], $tk->TEST_TIMEOUT);

        $newStream = $client->createMessageStream("test-stream", "Broadcasts", "Test Stream Name");
        $archivedStream = $client->archiveMessageStream($newStream->Id);

        $this->assertEquals($newStream->Id, $archivedStream->Id);
        $this->assertEquals($newStream->ServerId, $archivedStream->ServerId);
        $this->assertNotNull($archivedStream->ExpectedPurgeDate);

        $fetchedStream = $client->getMessageStream($archivedStream->Id);
        $this->assertNotNull($fetchedStream->ArchivedAt);
    }

    //unarchive message streams
    public function testClientCanUnarchiveStreams() {
        $tk = parent::$testKeys;
        $server = self::getNewServer();
        $client = new PostmarkClient($server->ApiTokens[0], $tk->TEST_TIMEOUT);

        $newStream = $client->createMessageStream("test-stream", "Broadcasts", "Test Stream Name");
        $client->archiveMessageStream($newStream->Id);

        $unarchivedStream = $client->unArchiveMessageStream($newStream->Id);

        $this->assertNull($unarchivedStream->ArchivedAt);
    }

    private static function getNewServer(){
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        return $client->createServer('test-php-streams-' . uniqid());
    }
}

?>
