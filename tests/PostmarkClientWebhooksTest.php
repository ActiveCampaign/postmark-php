<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use Postmark\Models\Webhooks\HttpAuth;
use Postmark\Models\Webhooks\WebhookConfiguration;
use Postmark\Models\Webhooks\WebhookConfigurationBounceTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationClickTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationDeliveryTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationOpenTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationSpamComplaintTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationSubscriptionChange;
use Postmark\Models\Webhooks\WebhookConfigurationTriggers;
use Postmark\PostmarkClient as PostmarkClient;


class PostmarkClientWebhooksTest extends PostmarkClientBaseTest {

    public static function tearDownAfterClass(): void {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $configurations = $client->getWebhookConfigurations();

        foreach ($configurations->Webhooks as $key => $value) {
            if (preg_match('/test-php-url/', $value->getUrl())) {
                $client->deleteWebhookConfiguration($value->getID());
            }
        }
    }

    public static function setUpBeforeClass(): void {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $configurations = $client->getWebhookConfigurations();

        foreach ($configurations->getWebhooks() as $value) {
            if (preg_match('/test-php-url/', $value->getUrl())) {
                $client->deleteWebhookConfiguration($value->getID());
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
        $subscriptionChangeTrigger = new WebhookConfigurationSubscriptionChange(true);

        $triggers = new WebhookConfigurationTriggers($openTrigger, $clickTrigger, $deliveryTrigger, $bounceTrigger, $spamComplaintTrigger, $subscriptionChangeTrigger);

        $httpAuth = new HttpAuth("testUser", "testPass");
        $headers = array("X-Test-Header" => "Header");
        $url = "http://www.postmark.com/test-php-url";
        $messageStream = "outbound";

        $result = $client->createWebhookConfiguration($url, $messageStream, $httpAuth, $headers, $triggers);

        $this->assertNotEmpty($result->getID());
        $this->assertEquals($url, $result->getUrl(),);
        $this->assertEquals($messageStream, $result->getMessageStream());
        $this->assertEquals($httpAuth->getUsername(), $result->HttpAuth->getUsername());
        $this->assertEquals($httpAuth->getPassword(), $result->HttpAuth->getPassword());
        $this->assertEquals("X-Test-Header", $result->HttpHeaders[0]->Name);
        $this->assertEquals($headers["X-Test-Header"], $result->HttpHeaders[0]->Value);
        $this->assertEquals($triggers->getOpenSettings()->getEnabled(), $result->Triggers->getOpenSettings()->getEnabled());
        $this->assertEquals($triggers->getOpenSettings()->getPostFirstOpenOnly(), $result->Triggers->getOpenSettings()->getPostFirstOpenOnly());
        $this->assertEquals($triggers->getClickSettings()->getEnabled(), $result->Triggers->getClickSettings()->getEnabled());
        $this->assertEquals($triggers->getDeliverySettings()->getEnabled(), $result->Triggers->getDeliverySettings()->getEnabled());
        $this->assertEquals($triggers->getBounceSettings()->getEnabled(), $result->Triggers->getBounceSettings()->getEnabled());
        $this->assertEquals($triggers->getBounceSettings()->getIncludeContent(), $result->Triggers->getBounceSettings()->getIncludeContent());
        $this->assertEquals($triggers->getSpamComplaintSettings()->getEnabled(), $result->Triggers->getSpamComplaintSettings()->getEnabled());
        $this->assertEquals($triggers->getSpamComplaintSettings()->getIncludeContent(), $result->Triggers->getSpamComplaintSettings()->getIncludeContent());
        $this->assertEquals($triggers->getSubscriptionChangeSettings()->getEnabled(), $result->Triggers->getSubscriptionChangeSettings()->getEnabled());
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

        $result = $client->editWebhookConfiguration($configuration->getID(), $url);

        $this->assertEquals($configuration->getID(), $result->getID());
        $this->assertEquals($configuration->getUrl(), $result->getUrl());
        $this->assertEquals($configuration->getMessageStream(), $result->getMessageStream());
        $this->assertEquals($configuration->HttpAuth->getUsername(), $result->HttpAuth->getUsername());
        $this->assertEquals($configuration->HttpAuth->getPassword(), $result->HttpAuth->getPassword());
        $this->assertEquals($configuration->HttpHeaders[0]->Name, $result->HttpHeaders[0]->Name);
        $this->assertEquals($configuration->HttpHeaders[0]->Value, $result->HttpHeaders[0]->Value);
        $this->assertEquals($configuration->Triggers->getOpenSettings()->getEnabled(), $result->Triggers->getOpenSettings()->getEnabled());
        $this->assertEquals($configuration->Triggers->getOpenSettings()->getPostFirstOpenOnly(), $result->Triggers->getOpenSettings()->getPostFirstOpenOnly());
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

        $result = $client->editWebhookConfiguration($configuration->getID(), $newUrl, $newHttpAuth, $newHeaders, $newTriggers);

        $this->assertEquals($newUrl, $result->getUrl());
        $this->assertEquals($newHttpAuth->getUsername(), $result->HttpAuth->getUsername());
        $this->assertEquals($newHttpAuth->getPassword(), $result->HttpAuth->getPassword());
        $this->assertEquals("X-Test-New-Header", $result->HttpHeaders[0]->Name);
        $this->assertEquals($newHeaders["X-Test-New-Header"], $result->HttpHeaders[0]->Value);
        $this->assertEquals($newTriggers->getOpenSettings()->getEnabled(), $result->Triggers->getOpenSettings()->getEnabled());
        $this->assertEquals($newTriggers->getOpenSettings()->getPostFirstOpenOnly(), $result->Triggers->getOpenSettings()->getPostFirstOpenOnly());
    }

    //get
    public function testClientCanGetWebhookConfiguration() {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $url = "http://www.postmark.com/test-php-url";

        $configuration = $client->createWebhookConfiguration($url);

        $result = $client->getWebhookConfiguration($configuration->getID());

        $this->assertEquals($configuration->getID(), $result->getID());
        $this->assertEquals($configuration->getUrl(), $result->getUrl());
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

        $deleteResult = $client->deleteWebhookConfiguration($configuration->getID());

        $this->assertEquals(0, $deleteResult->ErrorCode);
    }
}
