<?php

namespace Postmark\Tests;

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/TestingKeys.php";

use Postmark\PostmarkClient;

class PostmarkClientEmailTest extends \PHPUnit_Framework_TestCase {

	static $testKeys;

	static function setUpBeforeClass() {
		//get the config keys for the various tests
		$testKeys = new TestingKeys();
	}

	function testConfirmPhpUnit(){
		$this->assertTrue(true, "This confirms that PHPUnit is configured properly.");
	}
}

?>