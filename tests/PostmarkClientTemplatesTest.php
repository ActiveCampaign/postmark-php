<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use Postmark\Models\PostmarkAttachment;
use Postmark\PostmarkClient as PostmarkClient;

class PostmarkClientTemplatesTest extends PostmarkClientBaseTest {

    public static function setUpBeforeClass(): void {
        $tk = parent::$testKeys;
        $client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

        $templates = $client->listTemplates();

        foreach ($templates->getTemplates() as $key => $value) {
            if (preg_match('/^test-php.+/', $value->getName())) {
                $client->deleteTemplate($value->getTemplateid());
            }
        }
    }

	public static function tearDownAfterClass(): void {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$templates = $client->listTemplates();

		foreach ($templates->getTemplates() as $key => $value) {
			if (preg_match('/^test-php.+/', $value->getName())) {
				$client->deleteTemplate($value->getTemplateid());
			}
		}
	}

	//create
	function testClientCanCreateTemplate() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
		
		// Creating a layout template
		$layoutResult = $client->createTemplate('test-php-template-layout-' . date('c'), NULL, "Hello <b>{{{@content}}}</b>!", "Hello {{{@content}}}!", null, "Layout");
		$this->assertNotEmpty($layoutResult->getTemplateId());
		$this->assertNotEmpty($layoutResult->getAlias());
		
		// Creating a standard template using that layout template
		$standardResult = $client->createTemplate('test-php-template-' . date('c'), "{{subject}}", "Hello <b>{{name}}</b>!", "Hello {{name}}!", null, "Standard", $layoutResult->getAlias());
		$this->assertNotEmpty($standardResult->getTemplateId());
		$this->assertEquals($layoutResult->getAlias(), $standardResult->getLayoutTemplate());
	}

	//edit
	function testClientCanEditTemplate() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
		$result = $client->createTemplate('test-php-template-' . date('c'), "{{subject}}", "Hello <b>{{name}}</b>!", "Hello {{name}}!");
		$firstVersion = $client->getTemplate($result->getTemplateId());

		$r2 = $client->editTemplate((string)$result->getTemplateId(), 'test-php-template-edited-' . date('c'), "{{subject}}!", "Hi <b>{{name}}</b>!", "Hi {{name}}!");
		$secondVersion = $client->getTemplate($r2->getTemplateId());

		$this->assertNotSame($firstVersion->getName(), $secondVersion->getName());
		$this->assertNotSame($firstVersion->getHtmlBody(), $secondVersion->getHtmlBody());
		$this->assertNotSame($firstVersion->getSubject(), $secondVersion->getSubject());
		$this->assertNotSame($firstVersion->getTextBody(), $secondVersion->getTextBody());
		$this->assertEquals($firstVersion->getTemplateType(), $secondVersion->getTemplateType());
		
		// Creating a layout template
		$layoutTemplate = $client->createTemplate('test-php-template-layout-' . date('c'), NULL, "Hello <b>{{{@content}}}</b>!", "Hello {{{@content}}}!", null, "Layout");
		
		// Adding a layout template to a standard template
		$r3 = $client->editTemplate((string)$result->getTemplateId(), NULL, NULL, NULL, NULL, NULL, $layoutTemplate->getAlias());
		$versionWithLayoutTemplate = $client->getTemplate($r3->getTemplateId());
		$this->assertEquals($layoutTemplate->getAlias(), $versionWithLayoutTemplate->getLayoutTemplate());
		
		// Removing the layout template
		$r4 = $client->editTemplate((string)$result->getTemplateId(), NULL, NULL, NULL, NULL, NULL, "");
		$versionWithoutLayoutTemplate = $client->getTemplate($r4->getTemplateId());
		$this->assertNull($versionWithoutLayoutTemplate->getLayoutTemplate());
	}

	//list
	function testClientCanListTemplates() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
		for ($i = 0; $i < 5; $i++) {
			$client->createTemplate('test-php-template-' . $i . '-' . date('c'), "{{subject}}", "Hello <b>{{name}}</b>!", "Hello {{name}}!");
		}
		
		// Listing all templates
		$result = $client->listTemplates();
		$this->assertNotEmpty($result->Templates);
		
		// Filtering Layout templates
		$layoutTemplate = $client->createTemplate('test-php-template-layout-' . date('c'), NULL, "Hello <b>{{{@content}}}</b>!", "Hello {{{@content}}}!", null, "Layout");
		$result = $client->listTemplates(100, 0, "Layout");
		$this->assertNotEmpty($result->Templates);
		
		// Filtering by LayoutTemplate
		$client->createTemplate('test-php-template-' . date('c'), "{{subject}}", "Hello <b>{{name}}</b>!", "Hello {{name}}!", null, "Standard", $layoutTemplate->getAlias());
		$result = $client->listTemplates(100, 0, "All", $layoutTemplate->getAlias());
		$this->assertNotEmpty($result->getTemplates());
	}

	//get
	function testClientCanGetTemplate() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
		$result = $client->createTemplate('test-php-template-' . date('c'), "{{subject}}", "Hello <b>{{name}}</b>!", "Hello {{name}}!");
		$currentVersion = $client->getTemplate($result->getTemplateId());

		$this->assertNotEmpty($currentVersion->getName());
		$this->assertNotEmpty($currentVersion->getSubject());
		$this->assertNotEmpty($currentVersion->getHtmlBody());
		$this->assertNotEmpty($currentVersion->getTextBody());
		$this->assertEquals($currentVersion->isActive(), true);
	}

	//delete
	function testClientCanDeleteTemplate() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
		$result = $client->createTemplate('test-php-template-' . date('c'), "{{subject}}", "Hello <b>{{name}}</b>!", "Hello {{name}}!");
		$deleteResult = $client->deleteTemplate((string)$result->getTemplateId());

		$this->assertEquals(0, $deleteResult->getErrorCode());
	}

	//validate
	function testClientCanValidateTemplate() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
		$result = $client->validateTemplate("{{subject}}", "Hello <b>{{name}}</b>!", "Hello {{name}}!", NULL, false, "", NULL);

		$this->assertNotEmpty($result);
	}

	//send
	function testClientCanSendMailWithTemplate() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
		$result = $client->createTemplate('test-php-template-' . date('c'), "{{subject}}", "Hello <b>{{name}}</b>!", "Hello {{name}}!");
		$emailResult = $client->sendEmailWithTemplate($tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
			$tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS, $result->getTemplateId(), array("subjectValue" => "Hello!"));

		$this->assertEquals(0, $emailResult->getErrorCode());
	}
	
	//send batch
	function testClientCanSendBatchMessagesWithTemplate() {
		$tk = parent::$testKeys;
		
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
		
		$result = $client->createTemplate('test-php-template-' . date('c'), "Subject", "Hello <b>{{name}}</b>!", "Hello {{name}}!");
		
		$batch = array();

		$attachment = PostmarkAttachment::fromRawData("attachment content", "hello.txt", "text/plain");

		for ($i = 0; $i < 5; $i++) {
			$payload = array(
				'From' => $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
				'To' => $tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS,
				'TemplateID' => $result->getTemplateId(),
				'TemplateModel' => array("name" => "Jones-" . $i),
				'TrackOpens' => true,
				'Headers' => array("X-Test-Header" => "Test Header Content", 'X-Test-Date-Sent' => date('c')),
				'Attachments' => array($attachment));

			$batch[] = $payload;
		}

		$response = $client->sendEmailBatchWithTemplate($batch);
		$this->assertNotEmpty($response, 'The client could not send a batch of messages.');
	}
}