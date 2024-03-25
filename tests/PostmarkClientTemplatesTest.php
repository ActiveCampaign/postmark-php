<?php

namespace Postmark\Tests;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

use Postmark\Models\PostmarkAttachment;
use Postmark\Models\TemplatedPostmarkMessage;
use Postmark\PostmarkClient;

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkClientTemplatesTest extends PostmarkClientBaseTest
{
    public static function setUpBeforeClass(): void
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $templates = $client->listTemplates();

        foreach ($templates->getTemplates() as $key => $value) {
            if (preg_match('/^test-php.+/', $value->getName())) {
                $client->deleteTemplate($value->getTemplateid());
            }
        }
    }

    public static function tearDownAfterClass(): void
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $templates = $client->listTemplates();

        foreach ($templates->getTemplates() as $key => $value) {
            if (preg_match('/^test-php.+/', $value->getName())) {
                $client->deleteTemplate($value->getTemplateid());
            }
        }
    }

    // create
    public function testClientCanCreateTemplate()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        // Creating a layout template
        $layoutResult = $client->createTemplate('test-php-template-layout-' . date('c'), null, 'Hello <b>{{{@content}}}</b>!', 'Hello {{{@content}}}!', null, 'Layout');
        $this->assertNotEmpty($layoutResult->getTemplateId());
        $this->assertNotEmpty($layoutResult->getAlias());

        // Creating a standard template using that layout template
        $standardResult = $client->createTemplate('test-php-template-' . date('c'), '{{subject}}', 'Hello <b>{{name}}</b>!', 'Hello {{name}}!', null, 'Standard', $layoutResult->getAlias());
        $this->assertNotEmpty($standardResult->getTemplateId());
        $this->assertEquals($layoutResult->getAlias(), $standardResult->getLayoutTemplate());
    }

    // edit
    public function testClientCanEditTemplate()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        $result = $client->createTemplate('test-php-template-' . date('c'), '{{subject}}', 'Hello <b>{{name}}</b>!', 'Hello {{name}}!');
        $firstVersion = $client->getTemplate($result->getTemplateId());

        $r2 = $client->editTemplate((string) $result->getTemplateId(), 'test-php-template-edited-' . date('c'), '{{subject}}!', 'Hi <b>{{name}}</b>!', 'Hi {{name}}!');
        $secondVersion = $client->getTemplate($r2->getTemplateId());

        $this->assertNotSame($firstVersion->getName(), $secondVersion->getName());
        $this->assertNotSame($firstVersion->getHtmlBody(), $secondVersion->getHtmlBody());
        $this->assertNotSame($firstVersion->getSubject(), $secondVersion->getSubject());
        $this->assertNotSame($firstVersion->getTextBody(), $secondVersion->getTextBody());
        $this->assertEquals($firstVersion->getTemplateType(), $secondVersion->getTemplateType());

        // Creating a layout template
        $layoutTemplate = $client->createTemplate('test-php-template-layout-' . date('c'), null, 'Hello <b>{{{@content}}}</b>!', 'Hello {{{@content}}}!', null, 'Layout');

        // Adding a layout template to a standard template
        $r3 = $client->editTemplate((string) $result->getTemplateId(), null, null, null, null, null, $layoutTemplate->getAlias());
        $versionWithLayoutTemplate = $client->getTemplate($r3->getTemplateId());
        $this->assertEquals($layoutTemplate->getAlias(), $versionWithLayoutTemplate->getLayoutTemplate());

        // Removing the layout template
        $r4 = $client->editTemplate((string) $result->getTemplateId(), null, null, null, null, null, '');
        $versionWithoutLayoutTemplate = $client->getTemplate($r4->getTemplateId());
        $this->assertNull($versionWithoutLayoutTemplate->getLayoutTemplate());
    }

    // list
    public function testClientCanListTemplates()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        for ($i = 0; $i < 5; ++$i) {
            $client->createTemplate('test-php-template-' . $i . '-' . date('c'), '{{subject}}', 'Hello <b>{{name}}</b>!', 'Hello {{name}}!');
        }

        // Listing all templates
        $result = $client->listTemplates();
        $this->assertNotEmpty($result->Templates);

        // Filtering Layout templates
        $layoutTemplate = $client->createTemplate('test-php-template-layout-' . date('c'), null, 'Hello <b>{{{@content}}}</b>!', 'Hello {{{@content}}}!', null, 'Layout');
        $result = $client->listTemplates(100, 0, 'Layout');
        $this->assertNotEmpty($result->Templates);

        // Filtering by LayoutTemplate
        $client->createTemplate('test-php-template-' . date('c'), '{{subject}}', 'Hello <b>{{name}}</b>!', 'Hello {{name}}!', null, 'Standard', $layoutTemplate->getAlias());
        $result = $client->listTemplates(100, 0, 'All', $layoutTemplate->getAlias());
        $this->assertNotEmpty($result->getTemplates());
    }

    // get
    public function testClientCanGetTemplate()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        $result = $client->createTemplate('test-php-template-' . date('c'), '{{subject}}', 'Hello <b>{{name}}</b>!', 'Hello {{name}}!');
        $currentVersion = $client->getTemplate($result->getTemplateId());

        $this->assertNotEmpty($currentVersion->getName());
        $this->assertNotEmpty($currentVersion->getSubject());
        $this->assertNotEmpty($currentVersion->getHtmlBody());
        $this->assertNotEmpty($currentVersion->getTextBody());
        $this->assertEquals($currentVersion->isActive(), true);
    }

    // delete
    public function testClientCanDeleteTemplate()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        $result = $client->createTemplate('test-php-template-' . date('c'), '{{subject}}', 'Hello <b>{{name}}</b>!', 'Hello {{name}}!');
        $deleteResult = $client->deleteTemplate((string) $result->getTemplateId());

        $this->assertEquals(0, $deleteResult->getErrorCode());
    }

    // validate
    public function testClientCanValidateTemplate()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        $result = $client->validateTemplate('{{subject}}', 'Hello <b>{{name}}</b>!', 'Hello {{name}}!', null, false, '', null);

        $this->assertNotEmpty($result);
    }

    // send
    public function testClientCanSendMailWithTemplate()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        $result = $client->createTemplate('test-php-template-' . date('c'), '{{subject}}', 'Hello <b>{{name}}</b>!', 'Hello {{name}}!');
        $emailResult = $client->sendEmailWithTemplate(
            $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
            $tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS,
            $result->getTemplateId(),
            ['subjectValue' => 'Hello!'],
            false,
            "TestTag",
            true,
            $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
            null, //cc
            null, //bcc
            null, // headers
            null, // attachments
            null, // tracklinks
            null, // metadata
            "php-test" // stream name
        );

        $this->assertEquals(0, $emailResult->getErrorCode());
        $this->assertSame('OK', $emailResult->getMessage());
        $this->assertSame($tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS, $emailResult->getTo());
        $this->assertNotEmpty($emailResult->getSubmittedAt());
        $this->assertNotEmpty($emailResult->getMessageID());
    }

    // send model
    public function testClientCanSendMailWithTemplateModel()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
        $result = $client->createTemplate('test-php-template-' . date('c'), '{{subject}}', 'Hello <b>{{name}}</b> from Template Model!', 'Hello {{name}} from Template Model!');

        $templatedModel = new TemplatedPostmarkMessage();
        $templatedModel->setFrom($tk->WRITE_TEST_SENDER_EMAIL_ADDRESS);
        $templatedModel->setTo($tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS);
        $templatedModel->setTemplateId($result->getTemplateId());
        $templatedModel->setTemplateModel(['subjectValue' => 'Hello!']);
        $templatedModel->setHeaders(['X-Test-Header' => 'Header.', 'X-Test-Header-2' => 'Test Header 2']);

        $emailResult = $client->sendEmailWithTemplateModel($templatedModel);

        $this->assertEquals(0, $emailResult->getErrorCode());
        $this->assertSame('OK', $emailResult->getMessage());
        $this->assertSame($tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS, $emailResult->getTo());
        $this->assertNotEmpty($emailResult->getSubmittedAt());
        $this->assertNotEmpty($emailResult->getMessageID());
    }

    // send batch
    public function testClientCanSendBatchMessagesWithTemplate()
    {
        $tk = parent::$testKeys;

        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $result = $client->createTemplate('test-php-template-' . date('c'), 'Subject', 'Hello <b>{{name}}</b>!', 'Hello {{name}}!');

        $batch = [];

        $attachment = PostmarkAttachment::fromRawData('attachment content', 'hello.txt', 'text/plain');

        for ($i = 0; $i < 5; ++$i) {
            $payload = [
                'From' => $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
                'To' => $tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS,
                'TemplateID' => $result->getTemplateId(),
                'TemplateModel' => ['name' => 'Jones-' . $i],
                'TrackOpens' => true,
                'Headers' => ['X-Test-Header' => 'Test Header Content', 'X-Test-Date-Sent' => date('c')],
                'Attachments' => [$attachment]];

            $batch[] = $payload;
        }

        $response = $client->sendEmailBatchWithTemplate($batch);
        $this->assertNotEmpty($response, 'The client could not send a batch of messages.');
    }
}
