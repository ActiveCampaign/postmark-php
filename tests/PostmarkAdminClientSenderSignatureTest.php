<?php

namespace Postmark\Tests;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

use Postmark\PostmarkAdminClient;
use Exception;

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkAdminClientSenderSignatureTest extends PostmarkClientBaseTest
{
    public static function tearDownAfterClass(): void
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $signatures = $client->listSenderSignatures();

        foreach ($signatures->getSenderSignatures() as $key => $value) {
            if (preg_match('/test-php.+/', $value->getName()) > 0) {
                $client->deleteSenderSignature($value->getID());
            }
        }
    }

    public function testClientCanGetSignatureList()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $sigs = $client->listSenderSignatures();

        $this->assertGreaterThan(0, $sigs->getTotalCount());
        $this->assertNotEmpty($sigs->getSenderSignatures());
    }

    public function testClientCanGetSingleSignature()
    {
        $tk = parent::$testKeys;

        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);
        $id = $client->listSenderSignatures()->getSenderSignatures()[0]->getID();
        $sig = $client->getSenderSignature($id);

        $this->assertNotEmpty($sig->getName());
    }

    public function testClientCanCreateSignature()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $i = $tk->WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE;
        $sender = str_replace('[TOKEN]', 'test-php-create' . date('U'), $i);
        $name = 'test-php-create-' . date('U');
        $note = 'This is a test note';

        $sig = $client->createSenderSignature($sender, $name, null, null, $note);

        $this->assertNotEmpty($sig->getID());
        $this->assertEquals($sender, $sig->getEmailAddress());
        $this->assertEquals($name, $sig->getName());
        $this->assertEquals($note, $sig->getConfirmationPersonalNote());
    }

    public function testClientCanEditSignature()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $name = 'test-php-edit-' . date('U');

        $i = $tk->WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE;
        $sender = str_replace('[TOKEN]', 'test-php-edit' . date('U'), $i);

        $exploded = explode('@', $tk->WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE);
        $returnPath = 'test.' . $exploded[1];

        $sig = $client->createSenderSignature($sender, $name, null, $returnPath);

        $updated = $client->editSenderSignature(
            $sig->getID(),
            $name . '-updated',
            null,
            'updated-' . $returnPath
        );

        $this->assertNotSame($sig->getName(), $updated->getName());
        $this->assertNotSame($sig->getReturnpathdomain(), $updated->getReturnpathdomain());
    }

    public function testClientCanDeleteSignature()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $i = $tk->WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE;
        $timestamp = date('U') . '-' . uniqid();
        // Create a unique email by replacing the [TOKEN] placeholder
        $sender = str_replace('[TOKEN]', 'test-php-delete-' . $timestamp, $i);
        
        // Validate the generated email is valid
        if (!filter_var($sender, FILTER_VALIDATE_EMAIL)) {
            $this->fail("Generated email address is invalid: $sender");
        }

        $name = 'test-php-delete-' . $timestamp;
        
        // First, try to clean up any existing signature with the same name
        $sigs = $client->listSenderSignatures()->getSenderSignatures();
        foreach ($sigs as $existing) {
            if ($existing->getName() === $name) {
                try {
                    $client->deleteSenderSignature($existing->getID());
                    // Wait a moment for deletion to process
                    sleep(2);
                } catch (Exception $e) {
                    // Continue if deletion fails
                    continue;
                }
            }
        }
        
        // Also try to clean up any signature with the same email address
        foreach ($sigs as $existing) {
            try {
                // Get the signature details to check the email
                $sigDetails = $client->getSenderSignature($existing->getID());
                if ($sigDetails->getEmailAddress() === $sender) {
                    $client->deleteSenderSignature($existing->getID());
                    sleep(2);
                }
            } catch (Exception $e) {
                // Continue if we can't check or delete
                continue;
            }
        }
        
        // Now try to create the signature
        $sig = $client->createSenderSignature($sender, $name);

        $client->deleteSenderSignature($sig->getID());

        $sigs = $client->listSenderSignatures()->getSenderSignatures();

        foreach ($sigs as $key => $value) {
            $this->assertNotSame($sig->getName(), $value->getName());
        }
    }

    public function testClientCanRequestNewVerificationForSignature()
    {
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
