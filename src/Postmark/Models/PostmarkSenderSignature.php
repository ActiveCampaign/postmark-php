<?php

namespace Postmark\Models;

class PostmarkSenderSignature
{
    public int $ID;
    public string $Domain;
    public string $EmailAddress;
    public string $ReplyToEmailAddress;
    public string $Name;
    public bool $Confirmed;

    public bool $SPFVerified;
    public string $SPFHost;
    public string $SPFTextValue;

    public bool $DKIMVerified;
    public bool $WeakDKIM;
    public string $DKIMHost;
    public string $DKIMTextValue;
    public string $DKIMPendingHost;
    public string $DKIMPendingTextValue;
    public string $DKIMRevokedHost;
    public string $DKIMRevokedTextValue;
    public string $DKIMUpdateStatus;

    public string $ReturnPathDomain;
    public bool $ReturnPathDomainVerified;
    public string $ReturnPathDomainCNAMEValue;

    public bool $SafeToRemoveRevokedKeyFromDNS;

    public string $ConfirmationPersonalNote;

    public function __construct(array $values)
    {
        $this->ID = !empty($values['ID']) ? $values['ID'] : 0;
        $this->Domain = !empty($values['Domain']) ? $values['Domain'] : '';
        $this->EmailAddress = !empty($values['EmailAddress']) ? $values['EmailAddress'] : '';
        $this->ReplyToEmailAddress = !empty($values['ReplyToEmailAddress']) ? $values['ReplyToEmailAddress'] : '';
        $this->Name = !empty($values['Name']) ? $values['Name'] : '';
        $this->Confirmed = !empty($values['Confirmed']) ? $values['Confirmed'] : false;

        $this->SPFVerified = !empty($values['SPFVerified']) ? $values['SPFVerified'] : false;
        $this->SPFHost = !empty($values['SPFHost']) ? $values['SPFHost'] : '';
        $this->SPFTextValue = !empty($values['SPFTextValue']) ? $values['SPFTextValue'] : '';

        $this->DKIMVerified = !empty($values['DKIMVerified']) ? $values['DKIMVerified'] : false;
        $this->WeakDKIM = !empty($values['WeakDKIM']) ? $values['WeakDKIM'] : false;
        $this->DKIMHost = !empty($values['DKIMHost']) ? $values['DKIMHost'] : '';
        $this->DKIMTextValue = !empty($values['DKIMTextValue']) ? $values['DKIMTextValue'] : '';
        $this->DKIMPendingHost = !empty($values['DKIMPendingHost']) ? $values['DKIMPendingHost'] : '';
        $this->DKIMPendingTextValue = !empty($values['DKIMPendingTextValue']) ? $values['DKIMPendingTextValue'] : '';
        $this->DKIMRevokedHost = !empty($values['DKIMRevokedHost']) ? $values['DKIMRevokedHost'] : '';
        $this->DKIMRevokedTextValue = !empty($values['DKIMRevokedTextValue']) ? $values['DKIMRevokedTextValue'] : '';
        $this->DKIMUpdateStatus = !empty($values['DKIMUpdateStatus']) ? $values['DKIMUpdateStatus'] : '';

        $this->ReturnPathDomain = !empty($values['ReturnPathDomain']) ? $values['ReturnPathDomain'] : '';
        $this->ReturnPathDomainVerified = !empty($values['ReturnPathDomainVerified']) ? $values['ReturnPathDomainVerified'] : false;
        $this->ReturnPathDomainCNAMEValue = !empty($values['ReturnPathDomainCNAMEValue']) ? $values['ReturnPathDomainCNAMEValue'] : '';

        $this->SafeToRemoveRevokedKeyFromDNS = !empty($values['SafeToRemoveRevokedKeyFromDNS']) ? $values['SafeToRemoveRevokedKeyFromDNS'] : false;
        $this->ConfirmationPersonalNote = !empty($values['ConfirmationPersonalNote']) ? $values['ConfirmationPersonalNote'] : '';
    }

    /**
     * @return int|mixed
     */
    public function getID(): mixed
    {
        return $this->ID;
    }

    /**
     * @param int|mixed $ID
     */
    public function setID(mixed $ID): PostmarkSenderSignature
    {
        $this->ID = $ID;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getDomain(): mixed
    {
        return $this->Domain;
    }

    /**
     * @param mixed|string $Domain
     */
    public function setDomain(mixed $Domain): PostmarkSenderSignature
    {
        $this->Domain = $Domain;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getEmailAddress(): mixed
    {
        return $this->EmailAddress;
    }

    /**
     * @param mixed|string $EmailAddress
     */
    public function setEmailAddress(mixed $EmailAddress): PostmarkSenderSignature
    {
        $this->EmailAddress = $EmailAddress;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getReplyToEmailAddress(): mixed
    {
        return $this->ReplyToEmailAddress;
    }

    /**
     * @param mixed|string $ReplyToEmailAddress
     */
    public function setReplyToEmailAddress(mixed $ReplyToEmailAddress): PostmarkSenderSignature
    {
        $this->ReplyToEmailAddress = $ReplyToEmailAddress;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getName(): mixed
    {
        return $this->Name;
    }

    /**
     * @param mixed|string $Name
     */
    public function setName(mixed $Name): PostmarkSenderSignature
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * @return bool|mixed
     */
    public function getConfirmed(): mixed
    {
        return $this->Confirmed;
    }

    /**
     * @param bool|mixed $Confirmed
     */
    public function setConfirmed(mixed $Confirmed): PostmarkSenderSignature
    {
        $this->Confirmed = $Confirmed;

        return $this;
    }

    /**
     * @return bool|mixed
     */
    public function getSPFVerified(): mixed
    {
        return $this->SPFVerified;
    }

    /**
     * @param bool|mixed $SPFVerified
     */
    public function setSPFVerified(mixed $SPFVerified): PostmarkSenderSignature
    {
        $this->SPFVerified = $SPFVerified;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getSPFHost(): mixed
    {
        return $this->SPFHost;
    }

    /**
     * @param mixed|string $SPFHost
     */
    public function setSPFHost(mixed $SPFHost): PostmarkSenderSignature
    {
        $this->SPFHost = $SPFHost;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getSPFTextValue(): mixed
    {
        return $this->SPFTextValue;
    }

    /**
     * @param mixed|string $SPFTextValue
     */
    public function setSPFTextValue(mixed $SPFTextValue): PostmarkSenderSignature
    {
        $this->SPFTextValue = $SPFTextValue;

        return $this;
    }

    /**
     * @return bool|mixed
     */
    public function getDKIMVerified(): mixed
    {
        return $this->DKIMVerified;
    }

    /**
     * @param bool|mixed $DKIMVerified
     */
    public function setDKIMVerified(mixed $DKIMVerified): PostmarkSenderSignature
    {
        $this->DKIMVerified = $DKIMVerified;

        return $this;
    }

    /**
     * @return bool|mixed
     */
    public function getWeakDKIM(): mixed
    {
        return $this->WeakDKIM;
    }

    /**
     * @param bool|mixed $WeakDKIM
     */
    public function setWeakDKIM(mixed $WeakDKIM): PostmarkSenderSignature
    {
        $this->WeakDKIM = $WeakDKIM;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getDKIMHost(): mixed
    {
        return $this->DKIMHost;
    }

    /**
     * @param mixed|string $DKIMHost
     */
    public function setDKIMHost(mixed $DKIMHost): PostmarkSenderSignature
    {
        $this->DKIMHost = $DKIMHost;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getDKIMTextValue(): mixed
    {
        return $this->DKIMTextValue;
    }

    /**
     * @param mixed|string $DKIMTextValue
     */
    public function setDKIMTextValue(mixed $DKIMTextValue): PostmarkSenderSignature
    {
        $this->DKIMTextValue = $DKIMTextValue;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getDKIMPendingHost(): mixed
    {
        return $this->DKIMPendingHost;
    }

    /**
     * @param mixed|string $DKIMPendingHost
     */
    public function setDKIMPendingHost(mixed $DKIMPendingHost): PostmarkSenderSignature
    {
        $this->DKIMPendingHost = $DKIMPendingHost;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getDKIMPendingTextValue(): mixed
    {
        return $this->DKIMPendingTextValue;
    }

    /**
     * @param mixed|string $DKIMPendingTextValue
     */
    public function setDKIMPendingTextValue(mixed $DKIMPendingTextValue): PostmarkSenderSignature
    {
        $this->DKIMPendingTextValue = $DKIMPendingTextValue;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getDKIMRevokedHost(): mixed
    {
        return $this->DKIMRevokedHost;
    }

    /**
     * @param mixed|string $DKIMRevokedHost
     */
    public function setDKIMRevokedHost(mixed $DKIMRevokedHost): PostmarkSenderSignature
    {
        $this->DKIMRevokedHost = $DKIMRevokedHost;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getDKIMRevokedTextValue(): mixed
    {
        return $this->DKIMRevokedTextValue;
    }

    /**
     * @param mixed|string $DKIMRevokedTextValue
     */
    public function setDKIMRevokedTextValue(mixed $DKIMRevokedTextValue): PostmarkSenderSignature
    {
        $this->DKIMRevokedTextValue = $DKIMRevokedTextValue;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getDKIMUpdateStatus(): mixed
    {
        return $this->DKIMUpdateStatus;
    }

    /**
     * @param mixed|string $DKIMUpdateStatus
     */
    public function setDKIMUpdateStatus(mixed $DKIMUpdateStatus): PostmarkSenderSignature
    {
        $this->DKIMUpdateStatus = $DKIMUpdateStatus;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getReturnPathDomain(): mixed
    {
        return $this->ReturnPathDomain;
    }

    /**
     * @param mixed|string $ReturnPathDomain
     */
    public function setReturnPathDomain(mixed $ReturnPathDomain): PostmarkSenderSignature
    {
        $this->ReturnPathDomain = $ReturnPathDomain;

        return $this;
    }

    /**
     * @return bool|mixed
     */
    public function getReturnPathDomainVerified(): mixed
    {
        return $this->ReturnPathDomainVerified;
    }

    /**
     * @param bool|mixed $ReturnPathDomainVerified
     */
    public function setReturnPathDomainVerified(mixed $ReturnPathDomainVerified): PostmarkSenderSignature
    {
        $this->ReturnPathDomainVerified = $ReturnPathDomainVerified;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getReturnPathDomainCNAMEValue(): mixed
    {
        return $this->ReturnPathDomainCNAMEValue;
    }

    /**
     * @param mixed|string $ReturnPathDomainCNAMEValue
     */
    public function setReturnPathDomainCNAMEValue(mixed $ReturnPathDomainCNAMEValue): PostmarkSenderSignature
    {
        $this->ReturnPathDomainCNAMEValue = $ReturnPathDomainCNAMEValue;

        return $this;
    }

    /**
     * @return bool|mixed
     */
    public function getSafeToRemoveRevokedKeyFromDNS(): mixed
    {
        return $this->SafeToRemoveRevokedKeyFromDNS;
    }

    /**
     * @param bool|mixed $SafeToRemoveRevokedKeyFromDNS
     */
    public function setSafeToRemoveRevokedKeyFromDNS(mixed $SafeToRemoveRevokedKeyFromDNS): PostmarkSenderSignature
    {
        $this->SafeToRemoveRevokedKeyFromDNS = $SafeToRemoveRevokedKeyFromDNS;

        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getConfirmationPersonalNote(): mixed
    {
        return $this->ConfirmationPersonalNote;
    }

    /**
     * @param mixed|string $ConfirmationPersonalNote
     */
    public function setConfirmationPersonalNote(mixed $ConfirmationPersonalNote): PostmarkSenderSignature
    {
        $this->ConfirmationPersonalNote = $ConfirmationPersonalNote;

        return $this;
    }
}
