<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\ClientBehaviour;

use Postmark\ClientBehaviour\Webhooks;
use Postmark\Models\Webhooks\HttpAuth;
use Postmark\Models\Webhooks\WebhookConfigurationOpenTrigger;
use Postmark\Models\Webhooks\WebhookConfigurationTriggers;
use Postmark\PostmarkClient;
use Postmark\Tests\Unit\MockClientTestCase;
use Postmark\Tests\Unit\ResponseFixture;

/**
 * @see Webhooks
 */
class WebhooksTest extends MockClientTestCase
{
    private PostmarkClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        $client = new PostmarkClient('token', $this->mockClient);
        $this->client = $client->withBaseUri('https://example.com');
        $response = ResponseFixture::fromFileName('EmptyStubResponse.json', 200)->toResponse();
        $this->mockClient->setDefaultResponse($response);
    }

    /** @return array<string, array{0: string, 1: int, 2: string, 3: string}> */
    public function singleIntegerIdArgumentMethodProvider(): array
    {
        return [
            'getWebhookConfiguration' => ['getWebhookConfiguration', 41, '/webhooks/41', 'GET'],
            'deleteWebhookConfiguration' => ['deleteWebhookConfiguration', 42, '/webhooks/42', 'DELETE'],
        ];
    }

    /**
     * @covers \Postmark\ClientBehaviour\Webhooks::getWebhookConfiguration
     * @covers \Postmark\ClientBehaviour\Webhooks::deleteWebhookConfiguration
     * @dataProvider singleIntegerIdArgumentMethodProvider
     */
    public function testSingleIntegerIdMethods(string $method, int $id, string $expectPath, string $httpMethod): void
    {
        $this->client->$method($id);
        $this->assertLastRequestMethodWas($httpMethod);
        $this->assertLastRequestPathEquals($expectPath);
    }

    public function testGetWebhookConfigurations(): void
    {
        $this->client->getWebhookConfigurations();
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/webhooks');
        $this->assertQueryParameterIsAbsent('MessageStream');
    }

    public function testGetWebhookConfigurationsCanFilterByStream(): void
    {
        $this->client->getWebhookConfigurations('other');
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/webhooks');
        $this->assertQueryParameterValueEquals('MessageStream', 'other');
    }

    public function testWebhooksCanBeCreatedWithOnlyAUrl(): void
    {
        $this->client->createWebhookConfiguration('URL');
        $this->assertLastRequestMethodWas('POST');
        $this->assertLastRequestPathEquals('/webhooks');
        $this->assertBodyParameterValueEquals('Url', 'URL');
    }

    public function testWebhooksCanBeCreatedWithAdditionalParameters(): void
    {
        $auth = new HttpAuth('user', 'pass');
        $headers = ['foo' => 'bar'];
        $expectHeaders = [
            ['Name' => 'foo', 'Value' => 'bar'],
        ];
        $config = new WebhookConfigurationTriggers(new WebhookConfigurationOpenTrigger(true, false));
        $expectConfig = [
            'Open' => [
                'Enabled' => true,
                'PostFirstOpenOnly' => false,
            ],
        ];

        $this->client->createWebhookConfiguration('URL', 'other', $auth, $headers, $config);
        $this->assertBodyParameterValueEquals('MessageStream', 'other');
        $this->assertBodyParameterValueEquals('HttpAuth', $auth->jsonSerialize());
        $this->assertBodyParameterValueEquals('HttpHeaders', $expectHeaders);
        $this->assertBodyParameterValueEquals('Triggers', $expectConfig);
    }

    public function testWebhooksCanBeEdited(): void
    {
        $this->client->editWebhookConfiguration(99);
        $this->assertLastRequestMethodWas('PUT');
        $this->assertLastRequestPathEquals('/webhooks/99');
    }

    public function testWebhooksCanBeEditedWithAdditionalParameters(): void
    {
        $auth = new HttpAuth('user', 'pass');
        $headers = ['foo' => 'bar'];
        $expectHeaders = [
            ['Name' => 'foo', 'Value' => 'bar'],
        ];
        $config = new WebhookConfigurationTriggers(new WebhookConfigurationOpenTrigger(true, false));
        $expectConfig = [
            'Open' => [
                'Enabled' => true,
                'PostFirstOpenOnly' => false,
            ],
        ];

        $this->client->editWebhookConfiguration(99, 'URL', $auth, $headers, $config);
        $this->assertBodyParameterValueEquals('Url', 'URL');
        $this->assertBodyParameterValueEquals('HttpAuth', $auth->jsonSerialize());
        $this->assertBodyParameterValueEquals('HttpHeaders', $expectHeaders);
        $this->assertBodyParameterValueEquals('Triggers', $expectConfig);
    }
}
