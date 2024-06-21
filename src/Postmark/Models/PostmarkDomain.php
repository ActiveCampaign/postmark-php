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
    public bool $CustomTrackingVerified;

    public function __construct(array $values)
    {
        $this->ID = !empty($values['ID']) ? $values['ID'] : 0;
        $this->Name = !empty($values['Name']) ? $values['Name'] : '';
        $this->SPFVerified = !empty($values['SPFVerified']) ? $values['SPFVerified'] : false;
        $this->DKIMVerified = !empty($values['DKIMVerified']) ? $values['DKIMVerified'] : false;
        $this->WeakDKIM = !empty($values['WeakDKIM']) ? $values['WeakDKIM'] : false;
        $this->ReturnPathDomainVerified = !empty($values['ReturnPathDomainVerified']) ? $values['ReturnPathDomainVerified'] : false;
        $this->CustomTrackingVerified = !empty($values['CustomTrackingVerified']) ? $values['CustomTrackingVerified'] : false;
    }

    public function getID(): int
    {
        return $this->ID;
    }

    public function setID(int $ID): PostmarkDomain
    {
        $this->ID = $ID;

        return $this;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): PostmarkDomain
    {
        $this->Name = $Name;

        return $this;
    }

    public function isSPFVerified(): bool
    {
        return $this->SPFVerified;
    }

    public function setSPFVerified(bool $SPFVerified): PostmarkDomain
    {
        $this->SPFVerified = $SPFVerified;

        return $this;
    }

    public function isDKIMVerified(): bool
    {
        return $this->DKIMVerified;
    }

    public function setDKIMVerified(bool $DKIMVerified): PostmarkDomain
    {
        $this->DKIMVerified = $DKIMVerified;

        return $this;
    }

    public function isWeakDKIM(): bool
    {
        return $this->WeakDKIM;
    }

    public function setWeakDKIM(bool $WeakDKIM): PostmarkDomain
    {
        $this->WeakDKIM = $WeakDKIM;

        return $this;
    }

    public function isReturnPathDomainVerified(): bool
    {
        return $this->ReturnPathDomainVerified;
    }

    public function setReturnPathDomainVerified(bool $ReturnPathDomainVerified): PostmarkDomain
    {
        $this->ReturnPathDomainVerified = $ReturnPathDomainVerified;

        return $this;
    }

    public function getCustomTrackingVerified(): bool
    {
        return $this->CustomTrackingVerified;
    }

    public function setCustomTrackingVerified(bool $CustomTrackingVerified): PostmarkDomain
    {
        $this->CustomTrackingVerified = $CustomTrackingVerified;

        return $this;
    }
}
