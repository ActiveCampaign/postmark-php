<?php

namespace Postmark\Models;

class PostmarkDomain
{
    public int $ID;
    public string $Name;
    public bool $SPFVerified;
    public bool $DKIMVerified;
    public bool $WeakDKIM;
    public bool $ReturnPathDomainVerified;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->ID = !empty($values['ID']) ? $values['ID'] : 0;
        $this->Name = !empty($values['Name']) ? $values['Name'] : "";
        $this->SPFVerified = !empty($values['SPFVerified']) ? $values['SPFVerified'] : false;
        $this->DKIMVerified = !empty($values['DKIMVerified']) ? $values['DKIMVerified'] : false;
        $this->WeakDKIM = !empty($values['WeakDKIM']) ? $values['WeakDKIM'] : false;
        $this->ReturnPathDomainVerified = !empty($values['ReturnPathDomainVerified']) ? $values['ReturnPathDomainVerified'] : false;
    }

    /**
     * @return int
     */
    public function getID(): int
    {
        return $this->ID;
    }

    /**
     * @param int $ID
     * @return PostmarkDomain
     */
    public function setID(int $ID): PostmarkDomain
    {
        $this->ID = $ID;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->Name;
    }

    /**
     * @param string $Name
     * @return PostmarkDomain
     */
    public function setName(string $Name): PostmarkDomain
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSPFVerified(): bool
    {
        return $this->SPFVerified;
    }

    /**
     * @param bool $SPFVerified
     * @return PostmarkDomain
     */
    public function setSPFVerified(bool $SPFVerified): PostmarkDomain
    {
        $this->SPFVerified = $SPFVerified;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDKIMVerified(): bool
    {
        return $this->DKIMVerified;
    }

    /**
     * @param bool $DKIMVerified
     * @return PostmarkDomain
     */
    public function setDKIMVerified(bool $DKIMVerified): PostmarkDomain
    {
        $this->DKIMVerified = $DKIMVerified;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWeakDKIM(): bool
    {
        return $this->WeakDKIM;
    }

    /**
     * @param bool $WeakDKIM
     * @return PostmarkDomain
     */
    public function setWeakDKIM(bool $WeakDKIM): PostmarkDomain
    {
        $this->WeakDKIM = $WeakDKIM;
        return $this;
    }

    /**
     * @return bool
     */
    public function isReturnPathDomainVerified(): bool
    {
        return $this->ReturnPathDomainVerified;
    }

    /**
     * @param bool $ReturnPathDomainVerified
     * @return PostmarkDomain
     */
    public function setReturnPathDomainVerified(bool $ReturnPathDomainVerified): PostmarkDomain
    {
        $this->ReturnPathDomainVerified = $ReturnPathDomainVerified;
        return $this;
    }
}