<?php

declare(strict_types=1);

namespace Postmark\Tests\Unit\ClientBehaviour;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Postmark\ClientBehaviour\Templates;
use Postmark\PostmarkClient;
use Postmark\Tests\Unit\MockClientTestCase;
use Postmark\Tests\Unit\ResponseFixture;

/** @see Templates */
#[CoversClass(Templates::class)]
class TemplatesTest extends MockClientTestCase
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

    /** @return array<string, array{0: string, 1: string|int, 2: string, 3: string}> */
    public static function singleStringIdArgumentMethodProvider(): array
    {
        return [
            'deleteTemplate with alias' => ['deleteTemplate', 'some-id', '/templates/some-id', 'DELETE'],
            'getTemplate with alias' => ['getTemplate', 'some-id', '/templates/some-id', 'GET'],
            'deleteTemplate with id' => ['deleteTemplate', 99, '/templates/99', 'DELETE'],
            'getTemplate with id' => ['getTemplate', 44, '/templates/44', 'GET'],
        ];
    }

    #[DataProvider('singleStringIdArgumentMethodProvider')]
    public function testSingleStringIdMethods(string $method, string|int $id, string $expectPath, string $httpMethod): void
    {
        $this->client->$method($id);
        $this->assertLastRequestMethodWas($httpMethod);
        $this->assertLastRequestPathEquals($expectPath);
    }

    public function testCreateTemplate(): void
    {
        $this->client->createTemplate(
            'Name',
            'Subject',
            'HTML',
            'TEXT',
            'alias',
            'Type',
            'Layout',
        );

        $this->assertLastRequestMethodWas('POST');
        $this->assertLastRequestPathEquals('/templates');

        $expect = [
            'name' => 'Name',
            'subject' => 'Subject',
            'htmlBody' => 'HTML',
            'textBody' => 'TEXT',
            'alias' => 'alias',
            'templateType' => 'Type',
            'layoutTemplate' => 'Layout',
        ];

        foreach ($expect as $name => $value) {
            $this->assertBodyParameterValueEquals($name, $value);
        }
    }

    public function testEditTemplate(): void
    {
        $this->client->editTemplate(
            'whatever',
            'Name',
            'Subject',
            'HTML',
            'TEXT',
            'alias',
            'Layout',
        );

        $this->assertLastRequestMethodWas('PUT');
        $this->assertLastRequestPathEquals('/templates/whatever');

        $expect = [
            'name' => 'Name',
            'subject' => 'Subject',
            'htmlBody' => 'HTML',
            'textBody' => 'TEXT',
            'alias' => 'alias',
            'layoutTemplate' => 'Layout',
        ];

        foreach ($expect as $name => $value) {
            $this->assertBodyParameterValueEquals($name, $value);
        }
    }

    public function testEditTemplateWithoutArguments(): void
    {
        $this->client->editTemplate('whatever');

        $this->assertLastRequestMethodWas('PUT');
        $this->assertLastRequestPathEquals('/templates/whatever');

        $missing = [
            'name',
            'subject',
            'htmlBody',
            'textBody',
            'alias',
            'layoutTemplate',
        ];

        foreach ($missing as $name) {
            $this->assertBodyParameterIsAbsent($name);
        }
    }

    public function testListTemplatesWithoutArguments(): void
    {
        $this->client->listTemplates();

        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/templates');

        $this->assertQueryParameterValueEquals('count', '100');
        $this->assertQueryParameterValueEquals('offset', '0');
        $this->assertQueryParameterValueEquals('templateType', 'All');
        $this->assertQueryParameterIsAbsent('layoutTemplate');
    }

    public function testListTemplatesWithArguments(): void
    {
        $this->client->listTemplates(42, 1, 'Other', 'Layout');

        $this->assertLastRequestMethodWas('GET');
        $this->assertLastRequestPathEquals('/templates');

        $this->assertQueryParameterValueEquals('count', '42');
        $this->assertQueryParameterValueEquals('offset', '1');
        $this->assertQueryParameterValueEquals('templateType', 'Other');
        $this->assertQueryParameterValueEquals('layoutTemplate', 'Layout');
    }

    public function testValidateTemplateWithNoArguments(): void
    {
        $this->client->validateTemplate();

        $this->assertLastRequestMethodWas('POST');
        $this->assertLastRequestPathEquals('/templates/validate');

        $this->assertBodyParameterIsAbsent('subject');
        $this->assertBodyParameterIsAbsent('htmlBody');
        $this->assertBodyParameterIsAbsent('textBody');
        $this->assertBodyParameterIsAbsent('testRenderModel');
        $this->assertBodyParameterIsAbsent('layoutTemplate');

        $this->assertBodyParameterValueEquals('inlineCssForHtmlTestRender', true);
        $this->assertBodyParameterValueEquals('templateType', 'Standard');
    }

    public function testValidateTemplateWithArguments(): void
    {
        $model = [
            'name' => 'Jim',
            'address' => [
                'line1' => 'Foo',
                'line2' => 'Bar',
            ],
        ];

        $this->client->validateTemplate(
            'Subject',
            'HTML',
            'TEXT',
            $model,
            false,
            'Foo',
            'Baz',
        );

        $this->assertLastRequestMethodWas('POST');
        $this->assertLastRequestPathEquals('/templates/validate');

        $this->assertBodyParameterValueEquals('subject', 'Subject');
        $this->assertBodyParameterValueEquals('htmlBody', 'HTML');
        $this->assertBodyParameterValueEquals('textBody', 'TEXT');
        $this->assertBodyParameterValueEquals('testRenderModel', $model);
        $this->assertBodyParameterValueEquals('inlineCssForHtmlTestRender', false);
        $this->assertBodyParameterValueEquals('templateType', 'Foo');
        $this->assertBodyParameterValueEquals('layoutTemplate', 'Baz');
    }
}
