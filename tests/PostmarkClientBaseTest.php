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

        // Also here, not just in setUp: a few subclasses build a client in their own
        // setUpBeforeClass, which runs before any setUp, so a skip there is the only thing that can
        // stop the constructor throwing.
        if (!self::$testKeys->hasAnyCredentials()) {
            self::markTestSkipped(
                'Integration test: needs Postmark API credentials. Set the tokens from '
                . 'testing_keys.json.example as environment variables, or create testing_keys.json.'
            );
        }
    }

    /**
     * Skip, do not error, when the environment has no credentials.
     *
     * Every subclass builds a PostmarkClient from a token. With no tokens configured those
     * constructors throw "Argument #1 ($serverToken) must be of type string, null given", which
     * PHPUnit reports as 79 ERRORS -- indistinguishable, in CI, from 79 real regressions. That is
     * what made this pipeline unreadable: the suite has been red since the credentials went away,
     * so a genuine break had nowhere to show up. A skip states what is actually true -- the code
     * was not exercised because this environment cannot exercise it.
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (!self::$testKeys->hasAnyCredentials()) {
            $this->markTestSkipped(
                'Integration test: needs Postmark API credentials. Set the tokens from '
                . 'testing_keys.json.example as environment variables, or create testing_keys.json.'
            );
        }
    }

    /**
     * A sender address that is unique per run, whatever shape the prototype has.
     *
     * The signature tests did `str_replace('[TOKEN]', …, $prototype)`. That is only
     * unique if the configured prototype actually contains the placeholder —
     * testing_keys.json.example documents `anything+[token]@wildbit.com`, but the
     * value in use is a plain address with no placeholder, so the replace was a
     * no-op and every run tried to create the same signature, failing with "This
     * signature already exists". The case also never matched: the example is
     * lowercase and the tests replaced uppercase.
     */
    protected function uniqueSenderAddress(string $label): string
    {
        $prototype = (string) self::$testKeys->WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE;
        $token = $label . '-' . date('U') . '-' . uniqid();

        if (false !== stripos($prototype, '[token]')) {
            return str_ireplace('[TOKEN]', $token, $prototype);
        }

        // No placeholder configured — plus-tag the local part instead.
        return (string) preg_replace('/@/', '+' . $token . '@', $prototype, 1);
    }

    /**
     * Skip with a precise message when required credentials are absent.
     *
     * Without this an unset token surfaces as "Unauthorized: Missing or incorrect
     * API token" from the API, which reads like an SDK fault. Naming the missing
     * variable makes a configuration problem distinguishable from a code problem.
     */
    protected function requireKeys(string ...$names): void
    {
        $missing = [];

        foreach ($names as $name) {
            if (empty(self::$testKeys->{$name})) {
                $missing[] = $name;
            }
        }

        if ([] !== $missing) {
            $this->markTestSkipped(
                'Not configured for this environment: ' . implode(', ', $missing)
                . '. See testing_keys.json.example.'
            );
        }
    }

    /**
     * Skip when WRITE_TEST_SENDER_EMAIL_ADDRESS is not a confirmed Sender Signature.
     *
     * Every send in the suite uses it as the From address, so when the test account
     * loses the signature ~10 tests fail with "The 'From' address you supplied is not
     * a Sender Signature on your account" — an account-state problem wearing the
     * costume of a bug. Resolved once per run.
     */
    protected function requireConfirmedSenderSignature(): void
    {
        static $state = null;

        $this->requireKeys('WRITE_ACCOUNT_TOKEN', 'WRITE_TEST_SENDER_EMAIL_ADDRESS');

        if (null === $state) {
            $sender = self::$testKeys->WRITE_TEST_SENDER_EMAIL_ADDRESS;

            try {
                $client = new \Postmark\PostmarkAdminClient(
                    self::$testKeys->WRITE_ACCOUNT_TOKEN,
                    self::$testKeys->TEST_TIMEOUT
                );

                $state = false;

                foreach ($client->listSenderSignatures(500)->getSenderSignatures() as $signature) {
                    if (0 === strcasecmp($signature->getEmailAddress(), $sender)
                        && $signature->getConfirmed()) {
                        $state = true;

                        break;
                    }
                }
            } catch (\Throwable $e) {
                // Can't tell "unconfirmed" from "account unreachable"; say which.
                $state = 'unknown: ' . $e->getMessage();
            }
        }

        if (true !== $state) {
            $this->markTestSkipped(sprintf(
                'WRITE_TEST_SENDER_EMAIL_ADDRESS (%s) is not a confirmed Sender Signature on the '
                . 'test account, so every send in this suite would fail. Confirm it in the Postmark '
                . 'UI or point the variable at a confirmed address. (%s)',
                self::$testKeys->WRITE_TEST_SENDER_EMAIL_ADDRESS,
                is_string($state) ? $state : 'checked and absent/unconfirmed'
            ));
        }
    }
}
