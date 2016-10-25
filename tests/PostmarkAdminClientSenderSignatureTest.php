<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use Postmark\PostmarkAdminClient as PostmarkAdminClient;

class PostmarkAdminClientSenderSignatureTest extends PostmarkClientBaseTest {

	static function tearDownAfterClass() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$signatures = $client->listSenderSignatures();

		foreach ($signatures->senderSignatures as $key => $value) {
			if (preg_match('/test-php.+/', $value->name) > 0) {
				$client->deleteSenderSignature($value->id);
			}
		}
	}

	function testClientCanGetSignatureList() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$sigs = $client->listSenderSignatures();

		$this->assertGreaterThan(0, $sigs->totalCount);
		$this->assertNotEmpty($sigs->senderSignatures);
	}

	function testClientCanGetSingleSignature() {
		$tk = parent::$testKeys;

		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);
		$id = $client->listSenderSignatures()->senderSignatures[0]->id;
		$sig = $client->getSenderSignature($id);

		$this->assertNotEmpty($sig->name);
	}

	function testClientCanCreateSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$i = $tk->WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE;
		$sender = str_replace('[TOKEN]', 'test-php-create' . date('U'), $i);

		$sig = $client->createSenderSignature($sender, 'test-php-create-' . date('U'));

		$this->assertNotEmpty($sig->id);
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
			$sig->id, $name . '-updated', NULL, 'updated-' . $returnPath);

		$this->assertNotSame($sig->name, $updated->name);
		$this->assertNotSame($sig->returnpathdomain, $updated->returnpathdomain);
	}

	function testClientCanDeleteSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$i = $tk->WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE;
		$sender = str_replace('[TOKEN]', 'test-php-delete' . date('U'), $i);

		$name = 'test-php-delete-' . date('U');
		$sig = $client->createSenderSignature($sender, $name);

		$client->deleteSenderSignature($sig->id);

		$sigs = $client->listSenderSignatures()->senderSignatures;

		foreach ($sigs as $key => $value) {
			$this->assertNotSame($sig->name, $value->name);
		}

	}

	function testClientCanRequestNewVerificationForSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$i = $tk->WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE;
		$sender = str_replace('[TOKEN]', 'test-php-reverify' . date('U'), $i);

		$name = 'test-php-reverify-' . date('U');
		$sig = $client->createSenderSignature($sender, $name);

		$client->resendSenderSignatureConfirmation($sig->id);
	}

	function testClientCanVerifySPFForSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$name = 'test-php-spf-' . date('U');
		$i = $tk->WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE;

		$sender = str_replace('[TOKEN]', 'test-php-spf-' . date('U'), $i);

		$sig = $client->createSenderSignature($sender, $name);
		$client->verifySenderSignatureSPF($sig->id);
	}

}

?>