<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

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
		$result = $client->createTemplate('test-php-template-' . date('c'), "{{subject}}", "Hello <b>{{name}}</b>!", "Hello {{name}}!");
		$this->assertNotEmpty($result);
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
	}

	//list
	function testClientCanListTemplates() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_TEST_SERVER_TOKEN, $tk->TEST_TIMEOUT);
		for ($i = 0; $i < 5; $i++) {
			$client->createTemplate('test-php-template-' . $i . '-' . date('c'), "{{subject}}", "Hello <b>{{name}}</b>!", "Hello {{name}}!");
		}
		$result = $client->listTemplates();
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
}
?>