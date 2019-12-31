<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use Postmark\Models\Webhooks\HttpAuth;
use Postmark\Models\Webhooks\WebhookConfigurationBounceTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationClickTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationDeliveryTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationOpenTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationSpamComplaintTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationTriggers;
use Postmark\PostmarkClient as PostmarkClient;

class PostmarkClientWebhooksTest extends PostmarkClientBaseTest {

    private $testServerToken = "";

    public static function tearDownAfterClass() {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $configurations = $client->getWebhookConfigurations();

        foreach ($configurations->webhooks as $key => $value) {
            if (preg_match('/test-php-url/', $value->Url) > 0) {
                $client->deleteWebhookConfiguration($value->ID);
            }
        }
    }

    //create
    public function testClientCanCreateWebhookConfiguration() {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $openTrigger = new WebhookConfigurationOpenTrigger(true, true);
        $clickTrigger = new WebhookConfigurationClickTrigger(true);
        $deliveryTrigger = new WebhookConfigurationDeliveryTrigger(true);
        $bounceTrigger = new WebhookConfigurationBounceTrigger(true, true);
        $spamComplaintTrigger = new WebhookConfigurationSpamComplaintTrigger(true, true);
        $triggers = new WebhookConfigurationTriggers($openTrigger, $clickTrigger, $deliveryTrigger, $bounceTrigger, $spamComplaintTrigger);

        $httpAuth = new HttpAuth("testUser", "testPass");
        $headers = array("X-Test-Header" => "Header");
        $url = "http://www.postmark.com/test-php-url";
        $messageStream = "outbound";

        $result = $client->createWebhookConfiguration($url, $messageStream, $httpAuth, $headers, $triggers);

        $this->assertNotEmpty($result->ID);
        $this->assertEquals($url, $result->Url);
        $this->assertEquals($messageStream, $result->MessageStream);
        $this->assertEquals($httpAuth->getUsername(), $result->HttpAuth->Username);
        $this->assertEquals($httpAuth->getPassword(), $result->HttpAuth->Password);
        $this->assertEquals("X-Test-Header", $result->HttpHeaders[0]->Name);
        $this->assertEquals($headers["X-Test-Header"], $result->HttpHeaders[0]->Value);
        $this->assertEquals($triggers->getOpenSettings()->getEnabled(), $result->Triggers->Open->Enabled);
        $this->assertEquals($triggers->getOpenSettings()->getPostFirstOpenOnly(), $result->Triggers->Open->PostFirstOpenOnly);
        $this->assertEquals($triggers->getClickSettings()->getEnabled(), $result->Triggers->Click->Enabled);
        $this->assertEquals($triggers->getDeliverySettings()->getEnabled(), $result->Triggers->Delivery->Enabled);
        $this->assertEquals($triggers->getBounceSettings()->getEnabled(), $result->Triggers->Bounce->Enabled);
        $this->assertEquals($triggers->getBounceSettings()->getIncludeContent(), $result->Triggers->Bounce->IncludeContent);
        $this->assertEquals($triggers->getSpamComplaintSettings()->getEnabled(), $result->Triggers->SpamComplaint->Enabled);
        $this->assertEquals($triggers->getSpamComplaintSettings()->getIncludeContent(), $result->Triggers->SpamComplaint->IncludeContent);
    }

    //edit with null parameters
    public function testClientEditingWebhookConfigurationsPassingNullsChangesNothing() {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $openTrigger = new WebhookConfigurationOpenTrigger(true, true);
        $triggers = new WebhookConfigurationTriggers($openTrigger);

        $httpAuth = new HttpAuth("testUser", "testPass");
        $headers = array("X-Test-Header" => "Header");
        $url = "http://www.postmark.com/test-php-url";
        $messageStream = "outbound";

        $configuration = $client->createWebhookConfiguration($url, $messageStream, $httpAuth, $headers, $triggers);

        $result = $client->editWebhookConfiguration($configuration->ID, $url);

        $this->assertEquals($configuration->ID, $result->ID);
        $this->assertEquals($configuration->Url, $result->Url);
        $this->assertEquals($configuration->MessageStream, $result->MessageStream);
        $this->assertEquals($configuration->HttpAuth->Username, $result->HttpAuth->Username);
        $this->assertEquals($configuration->HttpAuth->Password, $result->HttpAuth->Password);
        $this->assertEquals($configuration->HttpHeaders[0]->Name, $result->HttpHeaders[0]->Name);
        $this->assertEquals($configuration->HttpHeaders[0]->Value, $result->HttpHeaders[0]->Value);
        $this->assertEquals($configuration->Triggers->Open->Enabled, $result->Triggers->Open->Enabled);
        $this->assertEquals($configuration->Triggers->Open->PostFirstOpenOnly, $result->Triggers->Open->PostFirstOpenOnly);
    }

    //edit
    public function testClientCanEditWebhookConfigurations() {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $openTrigger = new WebhookConfigurationOpenTrigger(true, true);
        $triggers = new WebhookConfigurationTriggers($openTrigger);

        $httpAuth = new HttpAuth("testUser", "testPass");
        $headers = array("X-Test-Header" => "Header");
        $url = "http://www.postmark.com/test-php-url";
        $messageStream = "outbound";

        $configuration = $client->createWebhookConfiguration($url, $messageStream, $httpAuth, $headers, $triggers);

        $newUrl = "http://www.postmark.com/new-test-php-url";
        $newHttpAuth = new HttpAuth("newTestUser", "newTestPass");
        $newHeaders = array("X-Test-New-Header" => "New-Header");

        $newOpenTrigger = new WebhookConfigurationOpenTrigger(false, false);
        $newTriggers = new WebhookConfigurationTriggers($newOpenTrigger);

        $result = $client->editWebhookConfiguration($configuration->ID, $newUrl, $newHttpAuth, $newHeaders, $newTriggers);

        $this->assertEquals($newUrl, $result->Url);
        $this->assertEquals($newHttpAuth->getUsername(), $result->HttpAuth->Username);
        $this->assertEquals($newHttpAuth->getPassword(), $result->HttpAuth->Password);
        $this->assertEquals("X-Test-New-Header", $result->HttpHeaders[0]->Name);
        $this->assertEquals($newHeaders["X-Test-New-Header"], $result->HttpHeaders[0]->Value);
        $this->assertEquals($newTriggers->getOpenSettings()->getEnabled(), $result->Triggers->Open->Enabled);
        $this->assertEquals($newTriggers->getOpenSettings()->getPostFirstOpenOnly(), $result->Triggers->Open->PostFirstOpenOnly);
    }

    //get
    public function testClientCanGetWebhookConfiguration() {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $url = "http://www.postmark.com/test-php-url";

        $configuration = $client->createWebhookConfiguration($url);

        $result = $client->getWebhookConfiguration($configuration->ID);

        $this->assertEquals($configuration->ID, $result->ID);
        $this->assertEquals($configuration->Url, $result->Url);
    }

    //list
    public function testClientCanGetWebhookConfigurations() {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $url = "http://www.postmark.com/test-php-url";
        $client->createWebhookConfiguration($url);

        $result = $client->getWebhookConfigurations();

        $this->assertNotEmpty($result->Webhooks);
    }

    //delete
    public function testClientCanDeleteWebhookConfiguration() {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $url = "http://www.postmark.com/test-php-url";

        $configuration = $client->createWebhookConfiguration($url);

        $deleteResult = $client->deleteWebhookConfiguration($configuration->ID);

        $this->assertEquals(0, $deleteResult->ErrorCode);
    }
}

?>