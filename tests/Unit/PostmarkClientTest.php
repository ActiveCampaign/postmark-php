<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit;

use Postmark\PostmarkClient;

use function current;

class PostmarkClientTest extends MockClientTestCase
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

    public function testGetServer(): void
    {
        $this->client->getServer();
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/server');
    }

    public function testEditServerWithoutParams(): void
    {
        $this->client->editServer();
        $this->assertLastRequestMethodWas('PUT');
        $this->assertLastRequestPathEquals('/server');
    }

    public function testEditServerParams(): void
    {
        $this->client->editServer(
            'Fred',
            'green',
            true,
            false,
            'Hook In',
            'Bounce Hook',
            'Open Hook',
            false,
            true,
            'Inbound Domain',
            20,
            'Track Links',
            'Click Hook',
            'Delivery Hook'
        );

        $expect = [
            'Name' => 'Fred',
            'Color' => 'green',
            'RawEmailEnabled' => true,
            'SmtpApiActivated' => false,
            'InboundHookUrl' => 'Hook In',
            'BounceHookUrl' => 'Bounce Hook',
            'OpenHookUrl' => 'Open Hook',
            'PostFirstOpenOnly' => false,
            'TrackOpens' => true,
            'InboundDomain' => 'Inbound Domain',
            'InboundSpamThreshold' => 20,
            'trackLinks' => 'Track Links',
            'ClickHookUrl' => 'Click Hook',
            'DeliveryHookUrl' => 'Delivery Hook',
        ];

        foreach ($expect as $name => $value) {
            $this->assertBodyParameterValueEquals($name, $value);
        }
    }
}
