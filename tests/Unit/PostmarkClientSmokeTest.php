<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit;

use Http\Mock\Client as MockClient;
use PHPUnit\Framework\TestCase;
use Postmark\PostmarkClient;
use Psr\Http\Message\RequestInterface;

use function current;
use function json_decode;

use const JSON_THROW_ON_ERROR;

class PostmarkClientSmokeTest extends TestCase
{
    private MockClient $mockClient;
    private PostmarkClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockClient = new MockClient();
        $client = new PostmarkClient('token', $this->mockClient);
        $this->client = $client->withBaseUri('https://example.com');
        $response = ResponseFixture::fromFileName('EmptyStubResponse.json', 200)->toResponse();
        $this->mockClient->setDefaultResponse($response);
    }

    private function assertLastRequest(): RequestInterface
    {
        $request = $this->mockClient->getLastRequest();
        self::assertInstanceOf(RequestInterface::class, $request);

        return $request;
    }

    private function assertLastRequestMethodWas(string $method): void
    {
        $request = $this->assertLastRequest();
        self::assertEquals($method, $request->getMethod());
    }

    private function assertLastRequestPathEquals(string $expected): void
    {
        $request = $this->assertLastRequest();
        self::assertEquals($expected, $request->getUri()->getPath());
    }

    /** @return array<string, mixed> */
    private function bodyParams(): array
    {
        $body = $this->assertLastRequest()->getBody();
        self::assertJson((string) $body, 'Request body was not JSON');

        /** @psalm-var array<string, mixed> $data */
        $data = json_decode((string) $body, true, 512, JSON_THROW_ON_ERROR);

        return $data;
    }

    /** @param mixed $expect */
    private function assertBodyParameterValueEquals(string $name, $expect): void
    {
        $body = $this->bodyParams();
        self::assertArrayHasKey($name, $body);
        self::assertEquals($expect, $body[$name]);
    }

    private function assertBodyParameterIsAbsent(string $name): void
    {
        $body = $this->bodyParams();
        self::assertArrayNotHasKey($name, $body);
    }

    public function testSendEmailDoesNotHaveLinkTrackingValueWhenAbsent(): void
    {
        $this->client->sendEmail('foo', 'bar', 'baz');
        $this->assertLastRequestMethodWas('POST');
        $this->assertLastRequestPathEquals('/email');
        $this->assertBodyParameterIsAbsent('TrackLinks');
    }

    public function testSendEmailWillSetTrackLinksValue(): void
    {
        $this->client->sendEmail('foo', 'bar', 'baz', null, null, null, null, null, null, null, null, null, 'Yeah!');
        $this->assertBodyParameterValueEquals('TrackLinks', 'Yeah!');
    }

    public function testTemplateSendingWithAndWithoutLinkTracking(): void
    {
        $this->client->sendEmailWithTemplate('from', 'to', 'template', [], true, null, null, null, null, null, null, null, 'Trackers');
        $this->assertLastRequestMethodWas('POST');
        $this->assertLastRequestPathEquals('/email/withTemplate');
        $this->assertBodyParameterValueEquals('TrackLinks', 'Trackers');

        $this->client->sendEmailWithTemplate('from', 'to', 'template', []);
        $this->assertBodyParameterIsAbsent('TrackLinks');
    }

    public function testTemplateIdIsSetWhenIntegerGivenInSendTemplate(): void
    {
        $this->client->sendEmailWithTemplate('from', 'to', 9, []);
        $this->assertBodyParameterValueEquals('TemplateId', 9);
        $this->assertBodyParameterIsAbsent('TemplateAlias');
    }

    public function testTemplateAliasIsSetWhenStringGivenInSendTemplate(): void
    {
        $this->client->sendEmailWithTemplate('from', 'to', 'whut', []);
        $this->assertBodyParameterValueEquals('TemplateAlias', 'whut');
        $this->assertBodyParameterIsAbsent('TemplateId');
    }

    public function testEmailBatchRequest(): void
    {
        $this->client->sendEmailBatch([]);
        $this->assertLastRequestMethodWas('POST');
        $this->assertLastRequestPathEquals('/email/batch');
    }

    public function testEmailBatchHeadersAreConverted(): void
    {
        $expect = [
            ['Name' => 'A', 'Value' => 'B'],
            ['Name' => 'C', 'Value' => 'D'],
        ];

        $this->client->sendEmailBatch([
            [
                'To' => 'Me',
                'From' => 'Them',
                'Subject' => 'Foo',
                'Headers' => ['A' => 'B', 'C' => 'D'],
            ],
        ]);

        $params = $this->bodyParams();
        $email = current($params);
        self::assertIsArray($email);
        self::assertArrayHasKey('Headers', $email);
        self::assertEquals($expect, $email['Headers']);
    }

    public function testTemplateBatchRequest(): void
    {
        $this->client->sendEmailBatchWithTemplate([]);
        $this->assertLastRequestMethodWas('POST');
        $this->assertLastRequestPathEquals('/email/batchWithTemplates');
    }

    public function testTemplateBatchHeadersAreConverted(): void
    {
        $expect = [
            ['Name' => 'A', 'Value' => 'B'],
            ['Name' => 'C', 'Value' => 'D'],
        ];

        $this->client->sendEmailBatchWithTemplate([
            [
                'To' => 'Me',
                'From' => 'Them',
                'TemplateModel' => [],
                'Headers' => ['A' => 'B', 'C' => 'D'],
            ],
        ]);

        $params = $this->bodyParams();
        $messages = $params['Messages'] ?? [];
        self::assertIsArray($messages);
        $email = current($messages);
        self::assertIsArray($email);
        self::assertArrayHasKey('Headers', $email);
        self::assertEquals($expect, $email['Headers']);
    }
}
