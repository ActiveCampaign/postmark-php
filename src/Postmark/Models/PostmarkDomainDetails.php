<?php

namespace Postmark\Models;

class PostmarkDomainDetails extends PostmarkDomain
{
    public ?string $SPFHost;
    public ?string $SPFTextValue;
    public ?string $DKIMHost;
    public ?string $DKIMTextValue;
    public ?string $DKIMPendingHost;
    public ?string $DKIMPendingTextValue;
    public ?string $DKIMRevokedHost;
    public ?string $DKIMRevokedTextValue;
    public bool $SafeToRemoveRevokedKeyFromDNS;
    public ?string $DKIMUpdateStatus;
    public ?string $ReturnPathDomain;
    public ?string $ReturnPathDomainCNAMEValue;

    public function __construct(array $values)
    {
        parent::__construct($values);

        $this->SPFHost = !empty($values['SPFHost']) ? $values['SPFHost'] : '';
        $this->SPFTextValue = !empty($values['SPFTextValue']) ? $values['SPFTextValue'] : '';
        $this->DKIMHost = !empty($values['DKIMHost']) ? $values['DKIMHost'] : '';
        $this->DKIMTextValue = !empty($values['DKIMTextValue']) ? $values['DKIMTextValue'] : '';
        $this->DKIMPendingHost = !empty($values['DKIMPendingHost']) ? $values['DKIMPendingHost'] : '';
        $this->DKIMPendingTextValue = !empty($values['DKIMPendingTextValue']) ? $values['DKIMPendingTextValue'] : '';
        $this->DKIMRevokedHost = !empty($values['DKIMRevokedHost']) ? $values['DKIMRevokedHost'] : '';
        $this->DKIMRevokedTextValue = !empty($values['DKIMRevokedTextValue']) ? $values['DKIMRevokedTextValue'] : '';
        $this->SafeToRemoveRevokedKeyFromDNS = !empty($values['SafeToRemoveRevokedKeyFromDNS']) ? $values['SafeToRemoveRevokedKeyFromDNS'] : false;
        $this->DKIMUpdateStatus = !empty($values['DKIMUpdateStatus']) ? $values['DKIMUpdateStatus'] : '';
        $this->ReturnPathDomain = !empty($values['ReturnPathDomain']) ? $values['ReturnPathDomain'] : '';
        $this->ReturnPathDomainCNAMEValue = !empty($values['ReturnPathDomainCNAMEValue']) ? $values['ReturnPathDomainCNAMEValue'] : '';
    }

    public function getSPFHost(): ?string
    {
        return $this->SPFHost;
    }

    public function setSPFHost(?string $SPFHost): PostmarkDomainDetails
    {
        $this->SPFHost = $SPFHost;

        return $this;
    }

    public function getSPFTextValue(): ?string
    {
        return $this->SPFTextValue;
    }

    public function setSPFTextValue(?string $SPFTextValue): PostmarkDomainDetails
    {
        $this->SPFTextValue = $SPFTextValue;

        return $this;
    }

    public function getDKIMHost(): ?string
    {
        return $this->DKIMHost;
    }

    public function setDKIMHost(?string $DKIMHost): PostmarkDomainDetails
    {
        $this->DKIMHost = $DKIMHost;

        return $this;
    }

    public function getDKIMTextValue(): ?string
    {
        return $this->DKIMTextValue;
    }

    public function setDKIMTextValue(?string $DKIMTextValue): PostmarkDomainDetails
    {
        $this->DKIMTextValue = $DKIMTextValue;

        return $this;
    }

    public function getDKIMPendingHost(): ?string
    {
        return $this->DKIMPendingHost;
    }

    public function setDKIMPendingHost(?string $DKIMPendingHost): PostmarkDomainDetails
    {
        $this->DKIMPendingHost = $DKIMPendingHost;

        return $this;
    }

    public function getDKIMPendingTextValue(): ?string
    {
        return $this->DKIMPendingTextValue;
    }

    public function setDKIMPendingTextValue(?string $DKIMPendingTextValue): PostmarkDomainDetails
    {
        $this->DKIMPendingTextValue = $DKIMPendingTextValue;

        return $this;
    }

    public function getDKIMRevokedHost(): ?string
    {
        return $this->DKIMRevokedHost;
    }

    public function setDKIMRevokedHost(?string $DKIMRevokedHost): PostmarkDomainDetails
    {
        $this->DKIMRevokedHost = $DKIMRevokedHost;

        return $this;
    }

    public function getDKIMRevokedTextValue(): ?string
    {
        return $this->DKIMRevokedTextValue;
    }

    public function setDKIMRevokedTextValue(?string $DKIMRevokedTextValue): PostmarkDomainDetails
    {
        $this->DKIMRevokedTextValue = $DKIMRevokedTextValue;

        return $this;
    }

    public function isSafeToRemoveRevokedKeyFromDNS(): bool
    {
        return $this->SafeToRemoveRevokedKeyFromDNS;
    }

    public function setSafeToRemoveRevokedKeyFromDNS(bool $SafeToRemoveRevokedKeyFromDNS): PostmarkDomainDetails
    {
        $this->SafeToRemoveRevokedKeyFromDNS = $SafeToRemoveRevokedKeyFromDNS;

        return $this;
    }

    public function getDKIMUpdateStatus(): ?string
    {
        return $this->DKIMUpdateStatus;
    }

    public function setDKIMUpdateStatus(?string $DKIMUpdateStatus): PostmarkDomainDetails
    {
        $this->DKIMUpdateStatus = $DKIMUpdateStatus;

        return $this;
    }

    public function getReturnPathDomain(): ?string
    {
        return $this->ReturnPathDomain;
    }

    public function setReturnPathDomain(?string $ReturnPathDomain): PostmarkDomainDetails
    {
        $this->ReturnPathDomain = $ReturnPathDomain;

        return $this;
    }

    public function getReturnPathDomainCNAMEValue(): ?string
    {
        return $this->ReturnPathDomainCNAMEValue;
    }

    public function setReturnPathDomainCNAMEValue(?string $ReturnPathDomainCNAMEValue): PostmarkDomainDetails
    {
        $this->ReturnPathDomainCNAMEValue = $ReturnPathDomainCNAMEValue;

        return $this;
    }
}
