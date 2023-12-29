<?php

namespace Postmark\Tests;

require_once __DIR__ . '/PostmarkClientBaseTest.php';

use Postmark\PostmarkAdminClient;

/**
 * @internal
 *
 * @coversNothing
 */
class PostmarkAdminClientDomainTest extends PostmarkClientBaseTest
{
    public static function tearDownAfterClass(): void
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $domains = $client->listDomains();

        foreach ($domains->getDomains() as $key => $value) {
            if (preg_match('/test-php.+/', $value->Name)) {
                $client->deleteDomain($value->ID);
            }
        }
    }

    public function testClientCanGetDomainList()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $domains = $client->listDomains();

        $this->assertGreaterThan(0, $domains->getTotalCount());
        $this->assertNotEmpty($domains->getDomains());
    }

    public function testClientCanGetSingleDomain()
    {
        $tk = parent::$testKeys;

        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $tempDomains = $client->listDomains()->getDomains();
        $id = $tempDomains[0]->getID();
        $domain = $client->getDomain($id);

        $this->assertNotEmpty($domain->getName());
    }

    public function testClientCanCreateDomain()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $domainName = 'test-php-create-' . $tk->WRITE_TEST_DOMAIN_NAME;

        $domain = $client->createDomain($domainName);

        $this->assertNotEmpty($domain->getID());
    }

    public function testClientCanEditDomain()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $domainName = 'test-php-edit-' . $tk->WRITE_TEST_DOMAIN_NAME;
        $returnPath = 'return.' . $domainName;

        $domain = $client->createDomain($domainName, $returnPath);

        $updated = $client->editDomain($domain->getID(), 'updated-' . $returnPath);

        $this->assertNotSame($domain->getReturnPathDomain(), $updated->getReturnPathDomain());
    }

    public function testClientCanDeleteDomain()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $domainName = $tk->WRITE_TEST_DOMAIN_NAME;

        $name = 'test-php-delete-' . $domainName;
        $domain = $client->createDomain($name);

        $client->deleteDomain($domain->getID());

        $domains = $client->listDomains()->getDomains();

        foreach ($domains as $key => $value) {
            $this->assertNotSame($domain->getName(), $value->getName());
        }
    }

    public function testClientCanVerifyDKIM()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $domains = $client->listDomains()->getDomains();
        foreach ($domains as $key => $value) {
            $verify = $client->verifyDKIM($value->ID);

            $this->assertSame($verify->getID(), $value->getID());
            $this->assertSame($verify->getName(), $value->getName());
        }
    }

    public function testClientCanVerifyReturnPath()
    {
        $tk = parent::$testKeys;
        $client = new PostmarkAdminClient($tk->WRITE_ACCOUNT_TOKEN, $tk->TEST_TIMEOUT);

        $domains = $client->listDomains()->getDomains();
        foreach ($domains as $key => $value) {
            $verify = $client->verifyReturnPath($value->getID());

            $this->assertSame($verify->getID(), $value->getID());
            $this->assertSame($verify->getName(), $value->getName());
        }
    }
}
