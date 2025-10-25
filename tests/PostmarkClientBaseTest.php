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
    
    /**
     * Get the first available verified sender signature or create one if needed
     */
    public static function getVerifiedSenderSignature()
    {
        try {
            $tk = self::$testKeys;
            $client = new \Postmark\PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);
            
            $signatures = $client->listSenderSignatures()->getSenderSignatures();
            
            if (!empty($signatures)) {
                // Return the first verified sender signature
                return $signatures[0]->getEmailAddress();
            }
            
            // If no signatures exist, try to create one using a unique email
            $uniqueEmail = 'test-' . uniqid() . '@wildbit.com';
            $client->createSenderSignature($uniqueEmail, 'Test Signature ' . uniqid());
            
            // Wait for the signature to be processed
            sleep(2);
            
            return $uniqueEmail;
        } catch (\Exception $e) {
            // If we can't get or create signatures, use the prototype as-is
            return $tk->WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE;
        }
    }
}
