<?php

declare(strict_types=1);

namespace Postmark\Tests\Legacy;

use PHPUnit\Framework\TestCase;
use Postmark\PostmarkClientBase;

abstract class PostmarkClientBaseTest extends TestCase {

	public static TestingKeys $testKeys;

    public static function setUpBeforeClass(): void
    {
		//get the config keys for the various tests
		self::$testKeys = new TestingKeys();
		PostmarkClientBase::$BASE_URL = self::$testKeys->BASE_URL ?: 'https://api.postmarkapp.com';
		date_default_timezone_set("UTC");
	}
}
