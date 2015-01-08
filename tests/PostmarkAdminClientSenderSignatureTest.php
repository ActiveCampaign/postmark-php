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

		throw new \Exception("Not yet implemented.", 1);
	}

	function testClientCanGetSingleSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN);

		throw new \Exception("Not yet implemented.", 1);
	}

	function testClientCanCreateSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN);

		throw new \Exception("Not yet implemented.", 1);
	}

	function testClientCanEditSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN);

		throw new \Exception("Not yet implemented.", 1);
	}

	function testClientCanDeleteSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN);

		throw new \Exception("Not yet implemented.", 1);
	}

	function testClientCanRequestNewVerificationForSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN);

		throw new \Exception("Not yet implemented.", 1);
	}

	function testClientCanRequestNewDKIMForSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN);

		throw new \Exception("Not yet implemented.", 1);
	}

	function testClientCanVerifySPFForSignature() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN);

		throw new \Exception("Not yet implemented.", 1);
	}

}

?>