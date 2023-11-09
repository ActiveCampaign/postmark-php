<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use Postmark\PostmarkAdminClient as PostmarkAdminClient;

class PostmarkAdminClientDomainTest extends PostmarkClientBaseTest {

	static function tearDownAfterClass(): void {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$domains = $client->listDomains();

		foreach ($domains->getDomains() as $key => $value) {
			if (preg_match('/test-php.+/', $value->Name)) {
				$client->deleteDomain($value->ID);
			}
		}
	}

	function testClientCanGetDomainList() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$domains = $client->listDomains();

		$this->assertGreaterThan(0, $domains->getTotalCount());
		$this->assertNotEmpty($domains->getDomains());
	}

	function testClientCanGetSingleDomain() {
		$tk = parent::$testKeys;

		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);
		$tempDomains = $client->listDomains()->getDomains();
		$id = $tempDomains[0]->getID();
		$domain = $client->getDomain($id);

		$this->assertNotEmpty($domain->getName());
	}

	function testClientCanCreateDomain() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$domainName = 'test-php-create-' . $tk->WRITE_TEST_DOMAIN_NAME;

		$domain = $client->createDomain($domainName);

		$this->assertNotEmpty($domain->getID());
	}

	function testClientCanEditDomain() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);
    
		$domainName = 'test-php-edit-' . $tk->WRITE_TEST_DOMAIN_NAME;
		$returnPath = 'return.' . $domainName;

		$domain = $client->createDomain($domainName, $returnPath);

		$updated = $client->editDomain($domain->getID(), 'updated-' . $returnPath);

		$this->assertNotSame($domain->getReturnPathDomain(), $updated->getReturnPathDomain());
	}

	function testClientCanDeleteDomain() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);
    
		$domainName = $tk->WRITE_TEST_DOMAIN_NAME;

		$name = 'test-php-delete-' . $domainName;
		$domain = $client->createDomain($name);

		$client->deleteDomain($domain->getID());

		$domains = $client->listDomains()->getDomains();

		foreach ($domains as $key => $value) {
			$this->assertNotSame($domain->getName(), $value->getName());
		}
	}

	function testClientCanVerifyDKIM() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$domains = $client->listDomains()->getDomains();
		foreach ($domains as $key => $value) {
			$verify = $client->verifyDKIM($value->ID);

			$this->assertSame($verify->getID(), $value->getID());
			$this->assertSame($verify->getName(), $value->getName());
		}
	}

	function testClientCanVerifyReturnPath() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$domains = $client->listDomains()->getDomains();
		foreach ($domains as $key => $value) {
			$verify = $client->verifyReturnPath($value->getID());

			$this->assertSame($verify->getID(), $value->getID());
			$this->assertSame($verify->getName(), $value->getName());
		}
	}

}

?>
