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

        // Clean up any leftover test webhooks from previous test runs
        try {
            $configurations = $this->client->getWebhookConfigurations();
            foreach ($configurations->getWebhooks() as $webhook) {
                if (preg_match('/test-php-url/', $webhook->Url)) {
                    try {
                        $this->client->deleteWebhookConfiguration($webhook->ID);
                    } catch (\Exception $e) {
                        // Ignore deletion errors during cleanup
                    }
                }
            }
        } catch (\Exception $e) {
            // Ignore errors during cleanup
        }

        // Create a fresh webhook for tests that need a shared webhook
        // Note: Most tests should create their own webhooks for better isolation
        $webhook = $this->client->createWebhookConfiguration(
            'http://example.com/test-php-url-' . uniqid(),
            'outbound'
        );
        $this->webhookId = $webhook->getID();
    }

    protected function tearDown(): void
    {
        // Clean up all created webhooks
        if ($this->client !== null) {
            // Clean up tracked webhooks
            foreach ($this->createdWebhookIds as $id) {
                try {
                    $this->client->deleteWebhookConfiguration($id);
                } catch (\Exception $e) {
                    // Ignore deletion errors during cleanup - webhook might already be deleted
                }
            }
            
            // Clean up the shared webhook if it still exists
            if ($this->webhookId !== null) {
                try {
                    $this->client->deleteWebhookConfiguration($this->webhookId);
                } catch (\Exception $e) {
                    // Ignore deletion errors during cleanup - webhook might already be deleted
                }
            }
        }
        
        // Reset state for next test
        $this->createdWebhookIds = [];
        $this->webhookId = null;
        
        parent::tearDown();
    }

    private function trackWebhookForCleanup(int $webhookId): void
    {
        $this->createdWebhookIds[] = $webhookId;
    }

    /**
     * Helper method to create a webhook for testing with automatic cleanup tracking
     */
    private function createTestWebhook(?string $urlSuffix = null, string $messageStream = 'outbound'): \Postmark\Models\Webhooks\WebhookConfiguration
    {
        $url = 'http://example.com/test-php-url-' . ($urlSuffix ?? uniqid());
        $webhook = $this->client->createWebhookConfiguration($url, $messageStream);
        $this->trackWebhookForCleanup($webhook->getID());
        return $webhook;
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
        $configuration = $this->createTestWebhook('get-test');

        $result = $this->client->getWebhookConfiguration($configuration->getID());

        $this->assertEquals($configuration->getID(), $result->getID());
        $this->assertEquals($configuration->getUrl(), $result->getUrl());
    }

    // list
    public function testClientCanGetWebhookConfigurations(): void
    {
        $configuration = $this->createTestWebhook('list-test');

        $result = $this->client->getWebhookConfigurations();

        $this->assertNotEmpty($result->Webhooks);
        
        // Verify our webhook is in the list
        $found = false;
        foreach ($result->Webhooks as $webhook) {
            if ($webhook->ID === $configuration->getID()) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, 'Created webhook should be found in the list');
    }

    // delete
    public function testClientCanDeleteWebhookConfiguration(): void
    {
        // Create a dedicated webhook for this test to ensure isolation
        $url = 'http://example.com/delete-test-php-url-' . uniqid();
        $webhook = $this->client->createWebhookConfiguration($url, 'outbound');
        $webhookId = $webhook->getID();
        
        // Verify webhook exists before deletion
        $retrievedWebhook = $this->client->getWebhookConfiguration($webhookId);
        $this->assertEquals($webhookId, $retrievedWebhook->getID());
        
        // Delete the webhook
        $result = $this->client->deleteWebhookConfiguration($webhookId);
        $this->assertInstanceOf(\Postmark\Models\PostmarkResponse::class, $result);
        $this->assertEquals(0, $result->getErrorCode());
        
        // Verify webhook is actually deleted by attempting to retrieve it
        $this->expectException(\Postmark\Models\PostmarkException::class);
        $this->expectExceptionMessage('The webhook for the provided \'ID\' was not found.');
        $this->client->getWebhookConfiguration($webhookId);
    }
}
