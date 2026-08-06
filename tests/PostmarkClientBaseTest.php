<?php

namespace Postmark\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/TestingKeys.php';

use Postmark\PostmarkClientBase;

abstract class PostmarkClientBaseTest extends \PHPUnit\Framework\TestCase
{
    public static $testKeys;

    public static function setUpBeforeClass(): void
    {
        // get the config keys for the various tests
        self::$testKeys = new TestingKeys();
        PostmarkClientBase::$BASE_URL = self::$testKeys->BASE_URL ?: 'https://api.postmarkapp.com';
        date_default_timezone_set('UTC');
    }
}
