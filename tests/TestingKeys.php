<?php

namespace Postmark\Tests;

class TestingKeys
{
    public $TEST_TIMEOUT;

    public $READ_INBOUND_TEST_SERVER_TOKEN;
    public $READ_SELENIUM_OPEN_TRACKING_TOKEN;
    public $READ_SELENIUM_TEST_SERVER_TOKEN;
    public $READ_LINK_TRACKING_TEST_SERVER_TOKEN;

    public $WRITE_ACCOUNT_TOKEN;
    public $WRITE_TEST_SERVER_TOKEN;
    public $WRITE_TEST_SENDER_EMAIL_ADDRESS;
    public $WRITE_TEST_EMAIL_RECIPIENT_ADDRESS;
    public $WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE;
    public $WRITE_TEST_DOMAIN_NAME;
    public $BASE_URL;

    public function __construct()
    {
        $test_keys = [];

        $keyfile = __DIR__ . '/../testing_keys.json';

        if (file_exists($keyfile)) {
            $keys = file_get_contents($keyfile);
            $test_keys = json_decode($keys, true);
        }

        $this->TEST_TIMEOUT = (int) (getenv('TEST_TIMEOUT') ?: '60');

        $this->READ_INBOUND_TEST_SERVER_TOKEN = getenv('READ_INBOUND_TEST_SERVER_TOKEN') ?: ($test_keys['READ_INBOUND_TEST_SERVER_TOKEN'] ?? null);
        $this->READ_SELENIUM_OPEN_TRACKING_TOKEN = getenv('READ_SELENIUM_OPEN_TRACKING_TOKEN') ?: ($test_keys['READ_SELENIUM_OPEN_TRACKING_TOKEN'] ?? null);
        $this->READ_SELENIUM_TEST_SERVER_TOKEN = getenv('READ_SELENIUM_TEST_SERVER_TOKEN') ?: ($test_keys['READ_SELENIUM_TEST_SERVER_TOKEN'] ?? null);
        $this->READ_LINK_TRACKING_TEST_SERVER_TOKEN = getenv('READ_LINK_TRACKING_TEST_SERVER_TOKEN') ?: ($test_keys['READ_LINK_TRACKING_TEST_SERVER_TOKEN'] ?? null);

        $this->WRITE_ACCOUNT_TOKEN = getenv('WRITE_ACCOUNT_TOKEN') ?: ($test_keys['WRITE_ACCOUNT_TOKEN'] ?? null);
        $this->WRITE_TEST_SERVER_TOKEN = getenv('WRITE_TEST_SERVER_TOKEN') ?: ($test_keys['WRITE_TEST_SERVER_TOKEN'] ?? null);
        $this->WRITE_TEST_SENDER_EMAIL_ADDRESS = getenv('WRITE_TEST_SENDER_EMAIL_ADDRESS') ?: ($test_keys['WRITE_TEST_SENDER_EMAIL_ADDRESS'] ?? null);
        $this->WRITE_TEST_EMAIL_RECIPIENT_ADDRESS = getenv('WRITE_TEST_EMAIL_RECIPIENT_ADDRESS') ?: ($test_keys['WRITE_TEST_EMAIL_RECIPIENT_ADDRESS'] ?? null);
        $this->WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE = getenv('WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE') ?: ($test_keys['WRITE_TEST_SENDER_SIGNATURE_PROTOTYPE'] ?? null);
        $this->WRITE_TEST_DOMAIN_NAME = getenv('WRITE_TEST_DOMAIN_NAME') ?: ($test_keys['WRITE_TEST_DOMAIN_NAME'] ?? null);

        $this->BASE_URL = getenv('BASE_URL') ?: ($test_keys['BASE_URL'] ?? null);
    }
}
