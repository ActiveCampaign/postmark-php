<?php

namespace Postmark\Tests;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

use Postmark\Models\PostmarkException;
use Postmark\Models\Suppressions\SuppressionChangeRequest;
use Postmark\PostmarkClient;

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkClientSuppressionsTest extends PostmarkClientBaseTest
{
    public static function tearDownAfterClass(): void
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        // remove all suppressions on the default stream
        $sups = $client->getSuppressions();
        foreach ($sups->getSuppressions() as $sup) {
            $suppressionChanges = [new SuppressionChangeRequest($sup->getEmailAddress())];
            $messageStream = 'outbound';
            $client->deleteSuppressions($suppressionChanges, $messageStream);
        }
    }

    // create suppression
    public function testClientCanCreateSuppressions()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $emailAddress = 'test-email@example.com';
        $suppressionChanges = [new SuppressionChangeRequest($emailAddress)];

        $messageStream = 'outbound';

        $result = $client->createSuppressions($suppressionChanges, $messageStream);

        $this->assertEquals($emailAddress, $result->getSuppressions()[0]->getEmailAddress());
        $this->assertEquals('Suppressed', $result->getSuppressions()[0]->getStatus());
    }

    // create suppression with default message stream
    public function testDefaultMessageStream()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $emailAddress = 'test-email@example.com';
        $suppressionChanges = [new SuppressionChangeRequest($emailAddress)];

        $result = $client->createSuppressions($suppressionChanges);

        $this->assertEquals($emailAddress, $result->getSuppressions()[0]->getEmailAddress());
        $this->assertEquals('Suppressed', $result->getSuppressions()[0]->getStatus());
    }

    // reactivate suppression
    public function testClientCanReactivateSuppressions()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $emailAddress = 'test-email@example.com';
        $suppressionChanges = [new SuppressionChangeRequest($emailAddress)];

        $messageStream = 'outbound';

        $result = $client->deleteSuppressions($suppressionChanges, $messageStream);

        $this->assertEquals($emailAddress, $result->getSuppressions()[0]->getEmailAddress());
        $this->assertEquals('Deleted', $result->getSuppressions()[0]->getStatus());
    }

    // invalid request returns failed Status
    public function testInvalidSuppressionChangeRequestReturnsFailedStatus()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $emailAddress = 'invalid-email';
        $suppressionChanges = [new SuppressionChangeRequest($emailAddress)];

        $messageStream = 'outbound';

        $result = $client->createSuppressions($suppressionChanges, $messageStream);

        $this->assertEquals($emailAddress, $result->getSuppressions()[0]->getEmailAddress());
        $this->assertEquals('Failed', $result->getSuppressions()[0]->getStatus());
    }

    // multiple requests are supported
    public function testClientCanCreateMultipleSuppressions()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $suppressionChanges = [];
        for ($i = 0; $i < 5; ++$i) {
            $emailAddress = "test-email-{$i}@example.com";
            $suppressionChanges[] = new SuppressionChangeRequest($emailAddress);
        }

        $messageStream = 'outbound';

        $result = $client->createSuppressions($suppressionChanges, $messageStream);

        $this->assertNotEmpty($result->getSuppressions());
        foreach ($result->getSuppressions() as $suppressionChangeResult) {
            $this->assertEquals('Suppressed', $suppressionChangeResult->getStatus());
        }
    }

    // invalid message stream throws exception
    public function testInvalidMessageStreamThrowsException()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $emailAddress = 'test-email@email.com';
        $suppressionChanges = [new SuppressionChangeRequest($emailAddress)];

        $messageStream = '123-invalid-stream-php-test';

        try {
            $result = $client->createSuppressions($suppressionChanges, $messageStream);
        } catch (PostmarkException $ex) {
            $this->assertEquals(422, $ex->getHttpStatusCode());
            $this->assertEquals("The message stream for the provided 'ID' was not found.", $ex->getMessage());
        }
    }

    // get suppressions
    public function testGetSuppressionsIsNotEmpty()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $result = $client->getSuppressions();
        $this->assertNotEmpty($result);
    }
}
