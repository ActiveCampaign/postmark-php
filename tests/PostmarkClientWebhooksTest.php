<?php

namespace Postmark\Tests;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

use Postmark\Models\Webhooks\HttpAuth;
use Postmark\Models\Webhooks\WebhookConfigurationBounceTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationClickTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationDeliveryTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationOpenTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationSpamComplaintTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationSubscriptionChangeTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationTriggers;
use Postmark\PostmarkClient;

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkClientWebhooksTest extends PostmarkClientBaseTest
{
    private ?int $webhookId = null;
    private ?PostmarkClient $client = null;
    private array $createdWebhookIds = [];

    protected function setUp(): void
    {
        parent::setUp();
        $tk = parent::$testKeys;
        $this->client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        // Clean up any leftover test webhooks
        $configurations = $this->client->getWebhookConfigurations();
        foreach ($configurations->getWebhooks() as $webhook) {
            if (preg_match('/test-php-url/', $webhook->Url)) {
                $this->client->deleteWebhook($webhook->ID);
            }
        }

        // Create a fresh webhook for tests
        $webhook = $this->client->createWebhook([
            'Url' => 'http://example.com/test-php-url-' . uniqid(),
            'MessageStream' => 'outbound',
            'HttpAuth' => [
                'Username' => 'testuser',
                'Password' => 'testpass'
            ],
            'HttpHeaders' => [
                ['Name' => 'X-Test', 'Value' => 'test']
            ]
        ]);
        $this->webhookId = $webhook['ID'];
    }

    protected function tearDown(): void
    {
        // Clean up all created webhooks
        if ($this->client !== null) {
            foreach ($this->createdWebhookIds as $id) {
                try {
                    $this->client->deleteWebhook($id);
                } catch (\Exception $e) {
                    // Ignore deletion errors during cleanup
                }
            }
            
            if ($this->webhookId !== null) {
                try {
                    $this->client->deleteWebhook($this->webhookId);
                } catch (\Exception $e) {
                    // Ignore deletion errors during cleanup
                }
            }
        }
        
        parent::tearDown();
    }

    private function trackWebhookForCleanup(int $webhookId): void
    {
        $this->createdWebhookIds[] = $webhookId;
    }

    // create
    public function testClientCanCreateWebhookConfiguration(): void
    {
        $openTrigger = new WebhookConfigurationOpenTrigger(true, true);
        $clickTrigger = new WebhookConfigurationClickTrigger(true);
        $deliveryTrigger = new WebhookConfigurationDeliveryTrigger(true);
        $bounceTrigger = new WebhookConfigurationBounceTrigger(true, true);
        $spamComplaintTrigger = new WebhookConfigurationSpamComplaintTrigger(true, true);
        $subscriptionChangeTrigger = new WebhookConfigurationSubscriptionChangeTrigger(true);

        $triggers = new WebhookConfigurationTriggers($openTrigger, $clickTrigger, $deliveryTrigger, $bounceTrigger, $spamComplaintTrigger, $subscriptionChangeTrigger);

        $httpAuth = new HttpAuth('testUser', 'testPass');
        $headers = ['X-Test-Header' => 'Header'];
        $url = 'http://www.postmark.com/test-php-url';
        $messageStream = 'outbound';

        $result = $this->client->createWebhookConfiguration($url, $messageStream, $httpAuth, $headers, $triggers);
        $this->trackWebhookForCleanup($result->getID());

        $this->assertNotEmpty($result->getID());
        $this->assertEquals($url, $result->getUrl());
        $this->assertEquals($messageStream, $result->getMessageStream());
        $this->assertEquals($httpAuth->getUsername(), $result->HttpAuth->getUsername());
        $this->assertEquals($httpAuth->getPassword(), $result->HttpAuth->getPassword());

        $this->assertEquals('X-Test-Header', $result->HttpHeaders[0]->Name);
        $this->assertEquals($headers['X-Test-Header'], $result->HttpHeaders[0]->Value);
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

    // edit with null parameters
    public function testClientEditingWebhookConfigurationsPassingNullsChangesNothing(): void
    {
        $openTrigger = new WebhookConfigurationOpenTrigger(true, true);
        $triggers = new WebhookConfigurationTriggers($openTrigger);

        $httpAuth = new HttpAuth('testUser', 'testPass');
        $headers = ['X-Test-Header' => 'Header'];
        $url = 'http://www.postmark.com/test-php-url';
        $messageStream = 'outbound';

        $configuration = $this->client->createWebhookConfiguration($url, $messageStream, $httpAuth, $headers, $triggers);
        $this->trackWebhookForCleanup($configuration->getID());

        $result = $this->client->editWebhookConfiguration($configuration->getID(), $url);

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

    // edit
    public function testClientCanEditWebhookConfigurations(): void
    {
        $openTrigger = new WebhookConfigurationOpenTrigger(true, true);
        $triggers = new WebhookConfigurationTriggers($openTrigger);

        $httpAuth = new HttpAuth('testUser', 'testPass');
        $headers = ['X-Test-Header' => 'Header'];
        $url = 'http://www.postmark.com/test-php-url';
        $messageStream = 'outbound';

        $configuration = $this->client->createWebhookConfiguration($url, $messageStream, $httpAuth, $headers, $triggers);
        $this->trackWebhookForCleanup($configuration->getID());

        $newUrl = 'http://www.postmark.com/new-test-php-url';
        $newHttpAuth = new HttpAuth('newTestUser', 'newTestPass');
        $newHeaders = ['X-Test-New-Header' => 'New-Header'];

        $newOpenTrigger = new WebhookConfigurationOpenTrigger(false, false);
        $newTriggers = new WebhookConfigurationTriggers($newOpenTrigger);

        $result = $this->client->editWebhookConfiguration($configuration->getID(), $newUrl, $newHttpAuth, $newHeaders, $newTriggers);

        $this->assertEquals($newUrl, $result->getUrl());
        $this->assertEquals($newHttpAuth->getUsername(), $result->HttpAuth->getUsername());
        $this->assertEquals($newHttpAuth->getPassword(), $result->HttpAuth->getPassword());
        $this->assertEquals('X-Test-New-Header', $result->HttpHeaders[0]->Name);
        $this->assertEquals($newHeaders['X-Test-New-Header'], $result->HttpHeaders[0]->Value);
        $this->assertEquals($newTriggers->getOpenSettings()->getEnabled(), $result->Triggers->getOpenSettings()->getEnabled());
        $this->assertEquals($newTriggers->getOpenSettings()->getPostFirstOpenOnly(), $result->Triggers->getOpenSettings()->getPostFirstOpenOnly());
    }

    // get
    public function testClientCanGetWebhookConfiguration(): void
    {
        $url = 'http://www.postmark.com/test-php-url';

        $configuration = $this->client->createWebhookConfiguration($url);
        $this->trackWebhookForCleanup($configuration->getID());

        $result = $this->client->getWebhookConfiguration($configuration->getID());

        $this->assertEquals($configuration->getID(), $result->getID());
        $this->assertEquals($configuration->getUrl(), $result->getUrl());
    }

    // list
    public function testClientCanGetWebhookConfigurations(): void
    {
        $url = 'http://www.postmark.com/test-php-url';
        $configuration = $this->client->createWebhookConfiguration($url);
        $this->trackWebhookForCleanup($configuration->getID());

        $result = $this->client->getWebhookConfigurations();

        $this->assertNotEmpty($result->Webhooks);
    }

    // delete
    public function testClientCanDeleteWebhookConfiguration(): void
    {
        $result = $this->client->deleteWebhook($this->webhookId);
        $this->assertTrue($result);
        
        // Mark as cleaned up to avoid double deletion
        $this->webhookId = null;
    }
}
