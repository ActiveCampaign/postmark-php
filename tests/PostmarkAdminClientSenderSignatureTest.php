<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use Postmark\PostmarkAdminClient as PostmarkAdminClient;

class PostmarkAdminClientSenderSignatureTest extends PostmarkClientBaseTest {

	static function tearDownAfterClass(): void {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$signatures = $client->listSenderSignatures();

		foreach ($signatures->getSenderSignatures() as $key => $value) {
			if (preg_match('/test-php.+/', $value->getName()) > 0) {
				$client->deleteSenderSignature($value->getID());
			}
		}
	}

	function testClientCanGetSignatureList() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$sigs = $client->listSenderSignatures();

		$this->assertGreaterThan(0, $sigs->getTotalCount());
		$this->assertNotEmpty($sigs->getSenderSignatures());
	}

	function testClientCanGetSingleSignature() {
		$tk = parent::$testKeys;

		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);
		$id = $client->listSenderSignatures()->getSenderSignatures()[0]->getID();
		$sig = $client->getSenderSignature($id);

		$this->assertNotEmpty($sig->getName());
	}

	function testClientCanCreateSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$i = $tk->WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE;
		$sender = str_replace('[TOKEN]', 'test-php-create' . date('U'), $i);

		$sig = $client->createSenderSignature($sender, 'test-php-create-' . date('U'));

		$this->assertNotEmpty($sig->getID());
	}

	function testClientCanEditSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$name = 'test-php-edit-' . date('U');

		$i = $tk->WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE;
		$sender = str_replace('[TOKEN]', 'test-php-edit' . date('U'), $i);

        $exploded = explode('@', $tk->WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE);
		$returnPath = 'test.' . $exploded[1];

		$sig = $client->createSenderSignature($sender, $name, NULL, $returnPath);

		$updated = $client->editSenderSignature(
			$sig->getID(), $name . '-updated', NULL, 'updated-' . $returnPath);

		$this->assertNotSame($sig->getName(), $updated->getName());
		$this->assertNotSame($sig->getReturnpathdomain(), $updated->getReturnpathdomain());
	}

	function testClientCanDeleteSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$i = $tk->WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE;
		$sender = str_replace('[TOKEN]', 'test-php-delete' . date('U'), $i);

		$name = 'test-php-delete-' . date('U');
		$sig = $client->createSenderSignature($sender, $name);

		$client->deleteSenderSignature($sig->getID());

		$sigs = $client->listSenderSignatures()->getSenderSignatures();

		foreach ($sigs as $key => $value) {
			$this->assertNotSame($sig->getName(), $value->getName());
		}

	}

	function testClientCanRequestNewVerificationForSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$i = $tk->WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE;
		$sender = str_replace('[TOKEN]', 'test-php-reverify' . date('U'), $i);

		$name = 'test-php-reverify-' . date('U');
		$sig = $client->createSenderSignature($sender, $name);

		$result = $client->resendSenderSignatureConfirmation($sig->getID());

		$this->assertEquals(0, $result->getErrorCode());
	}

}