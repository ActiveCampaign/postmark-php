<?php

namespace Postmark\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

require_once __DIR__ . '/TestingKeys.php';

use Postmark\PostmarkClientBase;

abstract class PostmarkClientBaseTest extends \PHPUnit\Framework\TestCase
{
    public static $testKeys;

    protected static function buildSenderSignatureEmail(string $prototype, string $prefix): string
    {
        $token = $prefix . str_replace('.', '', uniqid('', true));

        if (strpos($prototype, '[TOKEN]') !== false) {
            return str_replace('[TOKEN]', $token, $prototype);
        }

        $atPos = strpos($prototype, '@');
        if ($atPos !== false) {
            $local = substr($prototype, 0, $atPos);
            $domain = substr($prototype, $atPos + 1);

            return $local . '+' . $token . '@' . $domain;
        }

        return $token . '@example.com';
    }

    protected static function senderAddressForTest(TestingKeys $tk, string $prefix): string
    {
        if (!empty($tk->WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE)) {
            return self::buildSenderSignatureEmail($tk->WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE, $prefix);
        }

        return $tk->WRITE_TEST_SENDER_EMAIL_ADDRESS;
    }

    public static function setUpBeforeClass(): void
    {
        // get the config keys for the various tests
        self::$testKeys = new TestingKeys();
        PostmarkClientBase::$BASE_URL = self::$testKeys->BASE_URL ?: 'https://api.postmarkapp.com';
        date_default_timezone_set('UTC');
    }
}
