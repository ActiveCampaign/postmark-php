<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use Postmark\Models\PostmarkAttachment;
use Postmark\PostmarkClient as PostmarkClient;

class PostmarkClientTemplatesTest extends PostmarkClientBaseTest {

	private $testServerToken = "";

	static function tearDownAfterClass() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);

		$templates = $client->listTemplates();

		foreach ($templates->templates as $key => $value) {
			if (preg_match('/^test-php.+/', $value->name) > 0) {
				$client->deleteTemplate($value->templateid);
			}
		}
	}

	//create
	function testClientCanCreateTemplate() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
		
		// Creating a layout template
		$layoutResult = $client->createTemplate('test-php-template-layout-' . date('c'), NULL, "Hello <b>{{{@content}}}</b>!", "Hello {{{@content}}}!", null, "Layout");
		$this->assertNotEmpty($layoutResult->TemplateId);
		$this->assertNotEmpty($layoutResult->Alias);
		
		// Creating a standard template using that layout template
		$standardResult = $client->createTemplate('test-php-template-' . date('c'), "{{subject}}", "Hello <b>{{name}}</b>!", "Hello {{name}}!", null, "Standard", $layoutResult->Alias);
		$this->assertNotEmpty($standardResult->TemplateId);
		$this->assertEquals($layoutResult->Alias, $standardResult->LayoutTemplate);
	}

	//edit
	function testClientCanEditTemplate() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
		$result = $client->createTemplate('test-php-template-' . date('c'), "{{subject}}", "Hello <b>{{name}}</b>!", "Hello {{name}}!");
		$firstVersion = $client->getTemplate($result->TemplateId);

		$r2 = $client->editTemplate($result->TemplateId, 'test-php-template-edited-' . date('c'), "{{subject}}!", "Hi <b>{{name}}</b>!", "Hi {{name}}!");
		$secondVersion = $client->getTemplate($r2->TemplateId);

		$this->assertNotSame($firstVersion->Name, $secondVersion->Name);
		$this->assertNotSame($firstVersion->HtmlBody, $secondVersion->HtmlBody);
		$this->assertNotSame($firstVersion->Subject, $secondVersion->Subject);
		$this->assertNotSame($firstVersion->TextBody, $secondVersion->TextBody);
		$this->assertEquals($firstVersion->TemplateType, $secondVersion->TemplateType);
		
		// Creating a layout template
		$layoutTemplate = $client->createTemplate('test-php-template-layout-' . date('c'), NULL, "Hello <b>{{{@content}}}</b>!", "Hello {{{@content}}}!", null, "Layout");
		
		// Adding a layout template to a standard template
		$r3 = $client->editTemplate($result->TemplateId, NULL, NULL, NULL, NULL, NULL, $layoutTemplate->Alias);
		$versionWithLayoutTemplate = $client->getTemplate($r3->TemplateId);
		$this->assertEquals($layoutTemplate->Alias, $versionWithLayoutTemplate->LayoutTemplate);
		
		// Removing the layout template
		$r4 = $client->editTemplate($result->TemplateId, NULL, NULL, NULL, NULL, NULL, "");
		$versionWithoutLayoutTemplate = $client->getTemplate($r4->TemplateId);
		$this->assertNull($versionWithoutLayoutTemplate->LayoutTemplate);
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
		$client->createTemplate('test-php-template-' . date('c'), "{{subject}}", "Hello <b>{{name}}</b>!", "Hello {{name}}!", null, "Standard", $layoutTemplate->Alias);
		$result = $client->listTemplates(100, 0, "All", $layoutTemplate->Alias);
		$this->assertNotEmpty($result->Templates);
	}

	//get
	function testClientCanGetTemplate() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
		$result = $client->createTemplate('test-php-template-' . date('c'), "{{subject}}", "Hello <b>{{name}}</b>!", "Hello {{name}}!");
		$currentVersion = $client->getTemplate($result->TemplateId);

		$this->assertNotEmpty($currentVersion->Name);
		$this->assertNotEmpty($currentVersion->Subject);
		$this->assertNotEmpty($currentVersion->HtmlBody);
		$this->assertNotEmpty($currentVersion->TextBody);
		$this->assertEquals($currentVersion->Active, true);
	}

	//delete
	function testClientCanDeleteTemplate() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
		$result = $client->createTemplate('test-php-template-' . date('c'), "{{subject}}", "Hello <b>{{name}}</b>!", "Hello {{name}}!");
		$deleteResult = $client->deleteTemplate($result->TemplateId);

		$this->assertEquals(0, $deleteResult->ErrorCode);
	}

	//validate
	function testClientCanValidateTemplate() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
		$result = $client->validateTemplate("{{subject}}", "Hello <b>{{name}}</b>!", "Hello {{name}}!", NULL, false, NULL, NULL);

		$this->assertNotEmpty($result);
	}

	//send
	function testClientCanSendMailWithTemplate() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
		$result = $client->createTemplate('test-php-template-' . date('c'), "{{subject}}", "Hello <b>{{name}}</b>!", "Hello {{name}}!");
		$emailResult = $client->sendEmailWithTemplate($tk->WRITE_TEST_SENDER_EMAIL_ADDRESS,
			$tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS, $result->templateid, array("subjectValue" => "Hello!"));

		$this->assertEquals(0, $emailResult->ErrorCode);
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
				'TemplateID' => $result->TemplateId,
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
?>