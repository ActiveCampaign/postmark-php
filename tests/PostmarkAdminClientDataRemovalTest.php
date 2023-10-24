<?php

namespace Postmark\Tests;

require_once __DIR__ . "/PostmarkClientBaseTest.php";

use Postmark\PostmarkAdminClient as PostmarkAdminClient;

class PostmarkAdminClientDataRemovalTest extends PostmarkClientBaseTest {

    private $testId = 0;

	function testClientCanCreateRequest() {
		$tk = parent::$testKeys;
		$client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

		$drr = $client->createDataRemovalRequest("test@activecampaign.com", "test2@activecampaign.com", true);

        $this->testId = $drr->ID;
		$this->assertNotEmpty($drr->ID);
        $this->assertNotEmpty($drr->Status);
	}

    function testCheckDataRemovalRequest() {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $drr = $client->getDataRemoval($this->testId);

        $this->assertNotEmpty($drr->ID);
        $this->assertNotEmpty($drr->Status);
    }
}

?>