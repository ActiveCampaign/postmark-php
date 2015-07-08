<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use Postmark\PostmarkClient as PostmarkClient;
use Postmark\PostmarkClientBase as PostmarkClientBase;

class PostmarkClientTemplatesTest extends PostmarkClientBaseTest {

	private $testServerToken = "";

	static function tearDownAfterClass() {
		$tk = parent::$testKeys;
		$client = new PostmarkClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$templates = $client->listTemplates();

		foreach ($templates->templates as $key => $value) {
			if (preg_match('/^test-php.+/', $value->name) > 0) {
				$client->deleteTemplate($value->templateid);
			}
		}
	}

	function setUp() {
		PostmarkClientBase::$BASE_URL = "";
		$this->testServerToken = parent::$testKeys->WRITE_TEST_SERVER_TOKEN;
		parent::$testKeys->WRITE_TEST_SERVER_TOKEN = "";
	}

	function tearDown() {
		//TEMPORARY:
		self::tearDownAfterClass();

		PostmarkClientBase::$BASE_URL = parent::$testKeys->BASE_URL;
		parent::$testKeys->WRITE_TEST_SERVER_TOKEN = $this->testServerToken;
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

		$result = $client->editTemplate($result->TemplateId, 'test-php-template-edited-' . date('c'), "{{subject}}!", "Hi <b>{{name}}</b>!", "Hi {{name}}!");
		$secondVersion = $client . getTemplate($result->TemplateId);

		$this->assertNotEqual($firstVersion->Name, $secondVersion->Name);
		$this->assertNotEqual($firstVersion->HtmlBody, $secondVersion->HtmlBody);
		$this->assertNotEqual($firstVersion->Subject, $secondVersion->Subject);
		$this->assertNotEqual($firstVersion->TextBody, $secondVersion->TextBody);
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
		$result = $client->validateTemplate("{{subject}}", "Hello <b>{{name}}</b>!", "Hello {{name}}!", NULL, false);

		$this->assertNotEmpty($result);
	}

	//send
	function testClientCanSendMailWithTemplate() {
		$tk = parent::$testKeys;
		$result = $client->createTemplate('test-php-template-' . date('c'), "{{subject}}", "Hello <b>{{name}}</b>!", "Hello {{name}}!");
		$emailResult = $client->sendEmailWithTemplate($result->templateid, $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS, $tk->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS, array("subject" => "Hello!"));

		$this->assertEquals(0, $emailResult->ErrorCode);
	}
}
?>