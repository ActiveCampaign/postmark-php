<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Postmark\PostmarkAdminClient;

#[CoversClass(PostmarkAdminClient::class)]
class PostmarkAdminClientTest extends MockClientTestCase
{
    private PostmarkAdminClient $client;

    protected function setUp(): void
    {
        parent::setUp();

        $client = new PostmarkAdminClient('token', $this->mockClient);
        $this->client = $client->withBaseUri('https://example.com');
        $response = ResponseFixture::fromFileName('EmptyStubResponse.json', 200)->toResponse();
        $this->mockClient->setDefaultResponse($response);
    }

    /** @return array<string, array{0: string, 1: int, 2: string, 3: string}> */
    public static function singleIntegerIdArgumentMethodProvider(): array
    {
        return [
            'getServer' => ['getServer', 41, '/servers/41', 'GET'],
            'deleteServer' => ['deleteServer', 42, '/servers/42', 'DELETE'],
            'getSenderSignature' => ['getSenderSignature', 22, '/senders/22', 'GET'],
            'deleteSenderSignature' => ['deleteSenderSignature', 32, '/senders/32', 'DELETE'],
            'resendSenderSignatureConfirmation' => ['resendSenderSignatureConfirmation', 19, '/senders/19/resend', 'POST'],
            'getDomain' => ['getDomain', 4, '/domains/4', 'GET'],
            'deleteDomain' => ['deleteDomain', 7, '/domains/7', 'DELETE'],
            'verifyDomainSPF' => ['verifyDomainSPF', 7, '/domains/7/verifyspf', 'POST'],
            'rotateDKIMForDomain' => ['rotateDKIMForDomain', 7, '/domains/7/rotatedkim', 'POST'],
        ];
    }

    #[DataProvider('singleIntegerIdArgumentMethodProvider')]
    public function testSingleIntegerIdMethods(string $method, int $id, string $expectPath, string $httpMethod): void
    {
        $this->client->$method($id);
        $this->assertLastRequestMethodWas($httpMethod);
        $this->assertLastRequestPathEquals($expectPath);
    }

    public function testListServersWithoutArgument(): void
    {
        $this->client->listServers();
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/servers/');
        $this->assertQueryParameterValueEquals('count', '100');
        $this->assertQueryParameterValueEquals('offset', '0');
        $this->assertQueryParameterIsAbsent('name');
    }

    public function testListServersWithArguments(): void
    {
        $this->client->listServers(1, 2, 'foo');
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/servers/');
        $this->assertQueryParameterValueEquals('count', '1');
        $this->assertQueryParameterValueEquals('offset', '2');
        $this->assertQueryParameterValueEquals('name', 'foo');
    }

    public function testEditServerWithMinimalParameters(): void
    {
        $this->client->editServer(1);
        $this->assertLastRequestMethodWas('PUT');
        $this->assertLastRequestPathEquals('/servers/1');

        $absent = [
            'name',
            'color',
            'rawEmailEnabled',
            'smtpApiActivated',
            'inboundHookUrl',
            'bounceHookUrl',
            'openHookUrl',
            'postFirstOpenOnly',
            'trackOpens',
            'inboundDomain',
            'inboundSpamThreshold',
            'trackLinks',
            'ClickHookUrl',
            'DeliveryHookUrl',
            'EnableSmtpApiErrorHooks',
        ];

        foreach ($absent as $name) {
            $this->assertBodyParameterIsAbsent($name);
        }
    }

    public function testEditServerWithArguments(): void
    {
        $this->client->editServer(
            1,
            'Name',
            'Red',
            true,
            false,
            'Inbound',
            'Bounce',
            'Open',
            true,
            false,
            'Domain-In',
            23,
            'Track',
            'Click',
            'Deliver',
            true,
        );

        $expected = [
            'name' => 'Name',
            'color' => 'Red',
            'rawEmailEnabled' => true,
            'smtpApiActivated' => false,
            'inboundHookUrl' => 'Inbound',
            'bounceHookUrl' => 'Bounce',
            'openHookUrl' => 'Open',
            'postFirstOpenOnly' => true,
            'trackOpens' => false,
            'inboundDomain' => 'Domain-In',
            'inboundSpamThreshold' => 23,
            'trackLinks' => 'Track',
            'ClickHookUrl' => 'Click',
            'DeliveryHookUrl' => 'Deliver',
            'EnableSmtpApiErrorHooks' => true,
        ];

        foreach ($expected as $name => $value) {
            $this->assertBodyParameterValueEquals($name, $value);
        }
    }

    public function testCreateServerWithMinimalParameters(): void
    {
        $this->client->createServer('Name');
        $this->assertLastRequestMethodWas('POST');
        $this->assertLastRequestPathEquals('/servers/');

        $absent = [
            'color',
            'rawEmailEnabled',
            'smtpApiActivated',
            'inboundHookUrl',
            'bounceHookUrl',
            'openHookUrl',
            'postFirstOpenOnly',
            'trackOpens',
            'inboundDomain',
            'inboundSpamThreshold',
            'trackLinks',
            'ClickHookUrl',
            'DeliveryHookUrl',
            'EnableSmtpApiErrorHooks',
        ];

        $this->assertBodyParameterValueEquals('name', 'Name');

        foreach ($absent as $name) {
            $this->assertBodyParameterIsAbsent($name);
        }
    }

    public function testCreateServerWithArguments(): void
    {
        $this->client->createServer(
            'Name',
            'Red',
            true,
            false,
            'Inbound',
            'Bounce',
            'Open',
            true,
            false,
            'Domain-In',
            23,
            'Track',
            'Click',
            'Deliver',
            true,
        );

        $expected = [
            'name' => 'Name',
            'color' => 'Red',
            'rawEmailEnabled' => true,
            'smtpApiActivated' => false,
            'inboundHookUrl' => 'Inbound',
            'bounceHookUrl' => 'Bounce',
            'openHookUrl' => 'Open',
            'postFirstOpenOnly' => true,
            'trackOpens' => false,
            'inboundDomain' => 'Domain-In',
            'inboundSpamThreshold' => 23,
            'trackLinks' => 'Track',
            'ClickHookUrl' => 'Click',
            'DeliveryHookUrl' => 'Deliver',
            'EnableSmtpApiErrorHooks' => true,
        ];

        foreach ($expected as $name => $value) {
            $this->assertBodyParameterValueEquals($name, $value);
        }
    }

    public function testListSenders(): void
    {
        $this->client->listSenderSignatures();
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/senders/');
        $this->assertQueryParameterValueEquals('count', '100');
        $this->assertQueryParameterValueEquals('offset', '0');

        $this->client->listSenderSignatures(1, 2);
        $this->assertQueryParameterValueEquals('count', '1');
        $this->assertQueryParameterValueEquals('offset', '2');
    }

    public function testListDomains(): void
    {
        $this->client->listDomains();
        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/domains/');
        $this->assertQueryParameterValueEquals('count', '100');
        $this->assertQueryParameterValueEquals('offset', '0');

        $this->client->listDomains(1, 2);
        $this->assertQueryParameterValueEquals('count', '1');
        $this->assertQueryParameterValueEquals('offset', '2');
    }

    public function testCreateSenderSignature(): void
    {
        $this->client->createSenderSignature('FROM', 'NAME', 'REPLY', 'RETURN');
        $this->assertLastRequestMethodWas('POST');
        $this->assertLastRequestPathEquals('/senders/');

        $expect = [
            'fromEmail' => 'FROM',
            'name' => 'NAME',
            'replyToEmail' => 'REPLY',
            'returnPathDomain' => 'RETURN',
        ];

        foreach ($expect as $name => $value) {
            $this->assertBodyParameterValueEquals($name, $value);
        }
    }

    public function testEditSenderSignature(): void
    {
        $this->client->editSenderSignature(2, 'NAME', 'REPLY', 'RETURN');
        $this->assertLastRequestMethodWas('PUT');
        $this->assertLastRequestPathEquals('/senders/2');

        $expect = [
            'name' => 'NAME',
            'replyToEmail' => 'REPLY',
            'returnPathDomain' => 'RETURN',
        ];

        foreach ($expect as $name => $value) {
            $this->assertBodyParameterValueEquals($name, $value);
        }
    }

    public function testCreateDomain(): void
    {
        $this->client->createDomain('DOMAIN', 'RETURN');
        $this->assertLastRequestMethodWas('POST');
        $this->assertLastRequestPathEquals('/domains/');

        $expect = [
            'returnPathDomain' => 'RETURN',
            'name' => 'DOMAIN',
        ];

        foreach ($expect as $name => $value) {
            $this->assertBodyParameterValueEquals($name, $value);
        }
    }

    public function testEditDomain(): void
    {
        $this->client->editDomain(2, 'RETURN');
        $this->assertLastRequestMethodWas('PUT');
        $this->assertLastRequestPathEquals('/domains/2');
        $this->assertBodyParameterValueEquals('returnPathDomain', 'RETURN');
    }
}
