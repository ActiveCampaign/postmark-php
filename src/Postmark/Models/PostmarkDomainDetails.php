<?php

namespace Postmark\Models;

use Postmark\Models\PostmarkDomain;

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

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->SPFHost = !empty($values['SPFHost']) ? $values['SPFHost'] : "";
        $this->SPFTextValue = !empty($values['SPFTextValue']) ? $values['SPFTextValue'] : "";
        $this->DKIMHost = !empty($values['DKIMHost']) ? $values['DKIMHost'] : "";
        $this->DKIMTextValue = !empty($values['DKIMTextValue']) ? $values['DKIMTextValue'] : "";
        $this->DKIMPendingHost = !empty($values['DKIMPendingHost']) ? $values['DKIMPendingHost'] : "";
        $this->DKIMPendingTextValue = !empty($values['DKIMPendingTextValue']) ? $values['DKIMPendingTextValue'] : "";
        $this->DKIMRevokedHost = !empty($values['DKIMRevokedHost']) ? $values['DKIMRevokedHost'] : "";
        $this->DKIMRevokedTextValue = !empty($values['DKIMRevokedTextValue']) ? $values['DKIMRevokedTextValue'] : "";
        $this->SafeToRemoveRevokedKeyFromDNS = !empty($values['SafeToRemoveRevokedKeyFromDNS']) ? $values['SafeToRemoveRevokedKeyFromDNS'] : false;
        $this->DKIMUpdateStatus = !empty($values['DKIMUpdateStatus']) ? $values['DKIMUpdateStatus'] : "";
        $this->ReturnPathDomain = !empty($values['ReturnPathDomain']) ? $values['ReturnPathDomain'] : "";
        $this->ReturnPathDomainCNAMEValue = !empty($values['ReturnPathDomainCNAMEValue']) ? $values['ReturnPathDomainCNAMEValue'] : "";
    }


    /**
     * @return string|null
     */
    public function getSPFHost(): ?string
    {
        return $this->SPFHost;
    }

    /**
     * @param string|null $SPFHost
     * @return PostmarkDomainDetails
     */
    public function setSPFHost(?string $SPFHost): PostmarkDomainDetails
    {
        $this->SPFHost = $SPFHost;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSPFTextValue(): ?string
    {
        return $this->SPFTextValue;
    }

    /**
     * @param string|null $SPFTextValue
     * @return PostmarkDomainDetails
     */
    public function setSPFTextValue(?string $SPFTextValue): PostmarkDomainDetails
    {
        $this->SPFTextValue = $SPFTextValue;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDKIMHost(): ?string
    {
        return $this->DKIMHost;
    }

    /**
     * @param string|null $DKIMHost
     * @return PostmarkDomainDetails
     */
    public function setDKIMHost(?string $DKIMHost): PostmarkDomainDetails
    {
        $this->DKIMHost = $DKIMHost;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDKIMTextValue(): ?string
    {
        return $this->DKIMTextValue;
    }

    /**
     * @param string|null $DKIMTextValue
     * @return PostmarkDomainDetails
     */
    public function setDKIMTextValue(?string $DKIMTextValue): PostmarkDomainDetails
    {
        $this->DKIMTextValue = $DKIMTextValue;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDKIMPendingHost(): ?string
    {
        return $this->DKIMPendingHost;
    }

    /**
     * @param string|null $DKIMPendingHost
     * @return PostmarkDomainDetails
     */
    public function setDKIMPendingHost(?string $DKIMPendingHost): PostmarkDomainDetails
    {
        $this->DKIMPendingHost = $DKIMPendingHost;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDKIMPendingTextValue(): ?string
    {
        return $this->DKIMPendingTextValue;
    }

    /**
     * @param string|null $DKIMPendingTextValue
     * @return PostmarkDomainDetails
     */
    public function setDKIMPendingTextValue(?string $DKIMPendingTextValue): PostmarkDomainDetails
    {
        $this->DKIMPendingTextValue = $DKIMPendingTextValue;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDKIMRevokedHost(): ?string
    {
        return $this->DKIMRevokedHost;
    }

    /**
     * @param string|null $DKIMRevokedHost
     * @return PostmarkDomainDetails
     */
    public function setDKIMRevokedHost(?string $DKIMRevokedHost): PostmarkDomainDetails
    {
        $this->DKIMRevokedHost = $DKIMRevokedHost;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDKIMRevokedTextValue(): ?string
    {
        return $this->DKIMRevokedTextValue;
    }

    /**
     * @param string|null $DKIMRevokedTextValue
     * @return PostmarkDomainDetails
     */
    public function setDKIMRevokedTextValue(?string $DKIMRevokedTextValue): PostmarkDomainDetails
    {
        $this->DKIMRevokedTextValue = $DKIMRevokedTextValue;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSafeToRemoveRevokedKeyFromDNS(): bool
    {
        return $this->SafeToRemoveRevokedKeyFromDNS;
    }

    /**
     * @param bool $SafeToRemoveRevokedKeyFromDNS
     * @return PostmarkDomainDetails
     */
    public function setSafeToRemoveRevokedKeyFromDNS(bool $SafeToRemoveRevokedKeyFromDNS): PostmarkDomainDetails
    {
        $this->SafeToRemoveRevokedKeyFromDNS = $SafeToRemoveRevokedKeyFromDNS;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDKIMUpdateStatus(): ?string
    {
        return $this->DKIMUpdateStatus;
    }

    /**
     * @param string|null $DKIMUpdateStatus
     * @return PostmarkDomainDetails
     */
    public function setDKIMUpdateStatus(?string $DKIMUpdateStatus): PostmarkDomainDetails
    {
        $this->DKIMUpdateStatus = $DKIMUpdateStatus;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getReturnPathDomain(): ?string
    {
        return $this->ReturnPathDomain;
    }

    /**
     * @param string|null $ReturnPathDomain
     * @return PostmarkDomainDetails
     */
    public function setReturnPathDomain(?string $ReturnPathDomain): PostmarkDomainDetails
    {
        $this->ReturnPathDomain = $ReturnPathDomain;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getReturnPathDomainCNAMEValue(): ?string
    {
        return $this->ReturnPathDomainCNAMEValue;
    }

    /**
     * @param string|null $ReturnPathDomainCNAMEValue
     * @return PostmarkDomainDetails
     */
    public function setReturnPathDomainCNAMEValue(?string $ReturnPathDomainCNAMEValue): PostmarkDomainDetails
    {
        $this->ReturnPathDomainCNAMEValue = $ReturnPathDomainCNAMEValue;
        return $this;
    }


}