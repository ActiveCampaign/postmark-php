<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use Postmark\PostmarkAdminClient as PostmarkAdminClient;

class PostmarkAdminClientSenderSignatureTest extends PostmarkClientBaseTest {

	static function tearDownAfterClass() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN);

		$signatures = $client->listSenderSignatures();

		foreach ($signatures->senderSignatures as $key => $value) {
			if (preg_match('/^test-php.+/', $value->name) > 0) {
				$client->deleteSenderSignature($value->id);
			}
		}
	}

	function testClientCanGetSignatureList() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN);

		$sigs = $client->listSenderSignatures();

		$this->assertGreaterThan(0, $sigs->totalCount);
		$this->assertNotEmpty($sigs->senderSignatures);
	}

	function testClientCanGetSingleSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN);
		$id = $client->listSenderSignatures()->senderSignatures[0]->id;
		$sig = $client->getSenderSignature($id);

		$this->assertNotEmpty($sig->name);
	}

	function testClientCanCreateSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN);

		$sig = $client->createSenderSignature(
			'test-create-' . date('U') . '@example.com',
			'test-php-create-' . date('U'));

		$this->assertNotEmpty($sig->id);
	}

	function testClientCanEditSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN);

		$name = 'test-php-edit-' . date('U');
		$sig = $client->createSenderSignature(
			'test-edit-' . date('U') . '+' . '@example.com',
			$name);

		$updated = $client->editSenderSignature(
			$sig->id, $name . '-updated');

		$this->assertNotSame($sig->name, $updated->name);
	}

	function testClientCanDeleteSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN);

		$name = 'test-php-delete-' . date('U');
		$sig = $client->createSenderSignature(
			'test-delete-' . date('U') . '@example.com',
			$name);

		$client->deleteSenderSignature($sig->id);

		$sigs = $client->listSenderSignatures()->senderSignatures;

		foreach ($sigs as $key => $value) {
			$this->assertNotSame($sig->name, $value->name);
		}

	}

	function testClientCanRequestNewVerificationForSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN);

		$name = 'test-php-reverify-' . date('U');
		$sig = $client->createSenderSignature(
			'test-php-reverify-' . date('U') . '@example.com',
			$name);

		$client->resendSenderSignatureConfirmation($sig->id);
	}

	function testClientCanVerifySPFForSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN);

		$name = 'test-php-spf-' . date('U');
		$sig = $client->createSenderSignature(
			'test-spfcheck-' . date('U') . '@example.com',
			$name);

		$client->verifySenderSignatureSPF($sig->id);
	}

}

?>