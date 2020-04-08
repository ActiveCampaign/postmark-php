<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use Postmark\Models\PostmarkException;
use Postmark\Models\Suppressions\SuppressionChangeRequest;
use Postmark\PostmarkClient as PostmarkClient;

class PostmarkClientSuppressionsTest extends PostmarkClientBaseTest {

    private $testServerToken = "";

    public static function tearDownAfterClass() {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
    }

    //create suppression
    public function testClientCanCreateSuppressions() {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $emailAddress = "test-email@example.com";
        $suppressionChanges = array(new SuppressionChangeRequest($emailAddress));

        $messageStream = "outbound";

        $result = $client->createSuppressions($suppressionChanges, $messageStream);

        $this->assertEquals($emailAddress, $result->Suppressions[0]->EmailAddress);
        $this->assertEquals("Suppressed", $result->Suppressions[0]->Status);
    }

    //create suppression with default message stream
    public function testDefaultMessageStream() {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $emailAddress = "test-email@example.com";
        $suppressionChanges = array(new SuppressionChangeRequest($emailAddress));

        $result = $client->createSuppressions($suppressionChanges);

        $this->assertEquals($emailAddress, $result->Suppressions[0]->EmailAddress);
        $this->assertEquals("Suppressed", $result->Suppressions[0]->Status);
    }

    //reactivate suppression
    public function testClientCanReactivateSuppressions() {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $emailAddress = "test-email@example.com";
        $suppressionChanges = array(new SuppressionChangeRequest($emailAddress));

        $messageStream = "outbound";

        $result = $client->deleteSuppressions($suppressionChanges, $messageStream);

        $this->assertEquals($emailAddress, $result->Suppressions[0]->EmailAddress);
        $this->assertEquals("Deleted", $result->Suppressions[0]->Status);
    }

    //invalid request returns failed Status
    public function testInvalidSuppressionChangeRequestReturnsFailedStatus() {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $emailAddress = "invalid-email";
        $suppressionChanges = array(new SuppressionChangeRequest($emailAddress));

        $messageStream = "outbound";

        $result = $client->createSuppressions($suppressionChanges, $messageStream);

        $this->assertEquals($emailAddress, $result->Suppressions[0]->EmailAddress);
        $this->assertEquals("Failed", $result->Suppressions[0]->Status);
    }

    //multiple requests are supported
    public function testClientCanCreateMultipleSuppressions() {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $suppressionChanges = array();
        for($i = 0; $i < 5; $i++) {
            $emailAddress = "test-email-$i@example.com";
            $suppressionChanges[] = new SuppressionChangeRequest($emailAddress);
        }

        $messageStream = "outbound";

        $result = $client->createSuppressions($suppressionChanges, $messageStream);

        $this->assertNotEmpty($result->Suppressions);
        foreach($result->Suppressions as $suppressionChangeResult){
            $this->assertEquals("Suppressed", $suppressionChangeResult->Status);
        }
    }

    //invalid message stream throws exception
    public function testInvalidMessageStreamThrowsException() {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $emailAddress = "test-email@email.com";
        $suppressionChanges = array(new SuppressionChangeRequest($emailAddress));

        $messageStream = "123-invalid-stream-php-test";

        try {
            $result = $client->createSuppressions($suppressionChanges, $messageStream);
        } catch(PostmarkException $ex){
            $this->assertEquals(422, $ex->httpStatusCode);
            $this->assertEquals("The message stream for the provided 'ID' was not found.", $ex->message);
        }
    }

    //get suppressions
    public function testGetSuppressionsIsNotEmpty() {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $result = $client->getSuppressions();
        $this->assertNotEmpty($result);
    }
}

?>
