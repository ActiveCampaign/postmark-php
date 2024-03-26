<?php

namespace Postmark\Tests;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

use Postmark\Models;
use Postmark\PostmarkClient;

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkClientBounceTest extends PostmarkClientBaseTest
{
    public function testClientCanGetDeliveryStatistics()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $stats = $client->getDeliveryStatistics();

        $this->assertNotEmpty($stats, 'Stats from getDeliveryStatistics() should never be null or empty.');
        $this->assertGreaterThan(0, $stats->getInactiveMails(), 'The inactive mail count should be greater than zero.');
    }

    public function testClientCanGetBounces()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $bounces = $client->getBounces(10, 0);
        $this->assertNotEmpty($bounces);
    }

    public function testClientCanGetBounce()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        $bounces = $client->getBounces(10, 0);
        $id = $bounces->getBounces()[0]->getID();
        $bounce = $client->getBounce($id);
        $this->assertNotEmpty($bounce);
        $this->assertEquals($id, $bounce->getID());
    }

    public function testClientCanGetBounceDump()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->READ_SELENIUM_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        $bounces = $client->getBounces(10, 0);
        $id = $bounces->Bounces[0]->getID();
        $dump = $client->getBounceDump($id);
        $this->assertNotEmpty($dump);
        $this->assertNotEmpty($dump->getBody());
    }

    public function testClientCanActivateBounce()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        // generate a bounces
        $fromEmail = "andrew+client-testing@wildbit.com";
        $toEmail = "hardbounce@bounce-testing.postmarkapp.com"; // special email to generate bounce
        $subject = "Hello from Postmark!";
        $htmlBody = "<strong>Hello</strong> dear Postmark user.";
        $textBody = "Hello dear Postmark user.";
        $tag = "example-email-tag";
        $trackOpens = true;
        $trackLinks = "None";

        $sendResult = $client->sendEmail(
            $fromEmail,
            $toEmail,
            $subject,
            $htmlBody,
            $textBody,
            $tag,
            $trackOpens,
            NULL, // Reply To
            NULL, // CC
            NULL, // BCC
            NULL, // Header array
            NULL, // Attachment array
            $trackLinks,
            NULL // Metadata array
        );

        $bounces = $client->getBounces(10, 0);
        $id = 0;
        foreach ($bounces->getBounces() as $bounce)
        {
            if ($sendResult->getMessageID() == $bounce->getMessageID())
            {
                $id = $bounce->getID();
                break;
            }
        }

        $bounceActivation = $client->activateBounce($id);
        $bounce = $bounceActivation->getBounce();

        $this->assertNotEmpty($bounce);
        $this->assertEquals($id, $bounce->getID());
    }
}
