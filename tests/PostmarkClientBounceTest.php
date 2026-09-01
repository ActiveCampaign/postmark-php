<?php

namespace Postmark\Tests;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

use Postmark\PostmarkClient;

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkClientBounceTest extends PostmarkClientBaseTest
{
    public static function setUpBeforeClass(): void
    {
        // Chain first: the parent populates self::$testKeys and skips the class when no
        // credentials are configured. Without this, $testKeys is whatever an earlier test class
        // happened to leave in the shared static -- or null, and the client constructor throws.
        parent::setUpBeforeClass();

        PostmarkClientSuppressionsTest::tearDownAfterClass();
    }

    /**
     * @depends testClientCanActivateBounce
     */
    protected function setUp(): void
    {
        parent::setUp();

        // hasAnyCredentials() in the base setUp is true when ANY ONE of six tokens is set, so a
        // partially-configured environment passes it. Guard the token this class actually constructs
        // clients with, or the skip never fires and PostmarkClient's constructor throws
        // "Argument #1 ($serverToken) must be of type string, null given" — the exact fatal this
        // release exists to remove.
        // This class builds clients from BOTH tokens: the read path in testClientCanGetBounce /
        // testClientCanGetBounceDump, the write path in the activate tests.
        $this->requireKeys('READ_SELENIUM_TEST_SERVER_TOKEN', 'WRITE_TEST_SERVER_TOKEN');
        $this->requireConfirmedSenderSignature();
    }

    public function testClientCanGetBounce()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        $bounces = $client->getBounces(10, 0);
        $bounceList = $bounces->getBounces();
        
        if (empty($bounceList)) {
            $this->markTestSkipped('No bounces available for testing');
        }
        
        $id = $bounceList[0]->getID();
        $bounce = $client->getBounce($id);
        $this->assertNotEmpty($bounce);
        $this->assertEquals($id, $bounce->getID());
    }

    /**
     * @depends testClientCanActivateBounce
     */
    public function testClientCanGetBounceDump()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        $bounces = $client->getBounces(10, 0);
        $bounceList = $bounces->getBounces();
        
        if (empty($bounceList)) {
            $this->markTestSkipped('No bounces available for testing');
        }
        
        $id = $bounceList[0]->getID();
        $dump = $client->getBounceDump($id);
        $this->assertNotEmpty($dump);
        $this->assertNotEmpty($dump->getBody());
    }
    
    public function testClientCanActivateBounce()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        // make sure that this email is not suppressed
        // generate a bounces
        $fromEmail = $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS;
        $toEmail = 'hardbounce@bounce-testing.postmarkapp.com'; // special email to generate bounce
        $subject = 'Hello from Postmark!';
        $htmlBody = '<strong>Hello</strong> dear Postmark user.';
        $textBody = 'Hello dear Postmark user.';
        $tag = 'example-email-tag';
        $trackOpens = true;
        $trackLinks = 'None';

        $sendResult = $client->sendEmail(
            $fromEmail,
            $toEmail,
            $subject,
            $htmlBody,
            $textBody,
            $tag,
            $trackOpens,
            null, // Reply To
            null, // CC
            null, // BCC
            null, // Header array
            null, // Attachment array
            $trackLinks,
            null // Metadata array
        );

        // make sure there is enough time for the bounce to take place.
        sleep(180);

        $bounceList = $client->getBounces(20, 0);
        $id = 0;
        $sentId = $sendResult->getMessageID();
        $bounces = $bounceList->getBounces();

        $this->assertNotEmpty($bounces);
        $this->assertNotEmpty($sentId);

        foreach ($bounces as $bounce) {
            $bmid = $bounce->getMessageID();
            if ($sentId === $bmid) {
                $id = $bounce->getID();

                break;
            }
        }

        $this->assertGreaterThan(0, $id);

        $bounceActivation = $client->activateBounce($id);
        $actBounce = $bounceActivation->getBounce();

        $this->assertNotEmpty($actBounce);
        $this->assertEquals($id, $actBounce->getID());
    }

    /**
     * @depends testClientCanActivateBounce
     */
    public function testClientCanGetDeliveryStatistics()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $stats = $client->getDeliveryStatistics();

        $this->assertNotEmpty($stats, 'Stats from getDeliveryStatistics() should never be null or empty.');
        $this->assertGreaterThan(0, $stats->getInactiveMails(), 'The inactive mail count should be greater than zero.');
    }

    /**
     * @depends testClientCanActivateBounce
     */
    public function testClientCanGetBounces()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $bounces = $client->getBounces(10, 0);
        $this->assertNotEmpty($bounces);
    }
    
}
