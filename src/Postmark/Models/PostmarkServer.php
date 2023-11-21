<?php

namespace Postmark\Models;

class PostmarkServer
{
    public int $ID;
    public string $Name;
    public array $ApiTokens;
    public string $ServerLink;
    public string $Color;

    public bool $SmtpApiActivated;
    public bool $RawEmailEnabled;
    public string $DeliveryType;
    public string $InboundAddress;
    public string $InboundHookUrl;
    public string $BounceHookUrl;
    public string $OpenHookUrl;
    public bool $PostFirstOpenOnly;
    public bool $TrackOpens;

    public string $TrackLinks; // "None", "HtmlAndText", "HtmlOnly", or "TextOnly"
    public string $ClickHookUrl;
    public string $DeliveryHookUrl;
    public string $InboundDomain;
    public string $InboundHash;
    public int $InboundSpamThreshold;
    public bool $EnableSmtpApiErrorHooks;

    /**
     * @param array $values
     */
    public function __construct(mixed $values)
    {
        $this->ID = !empty($values['ID']) ? $values['ID'] : 0;
        $this->Name = !empty($values['Name']) ? $values['Name'] : "";
        $this->ApiTokens = !empty($values['ApiTokens']) ? $values['ApiTokens'] : array();
        $this->ServerLink = !empty($values['ServerLink']) ? $values['ServerLink'] : "";
        $this->Color = !empty($values['Color']) ? $values['Color'] : "";

        $this->SmtpApiActivated = !empty($values['SmtpApiActivated']) ? $values['SmtpApiActivated'] : false;
        $this->RawEmailEnabled = !empty($values['RawEmailEnabled']) ? $values['RawEmailEnabled'] : false;
        $this->DeliveryType = !empty($values['DeliveryType']) ? $values['DeliveryType'] : "";
        $this->InboundAddress = !empty($values['InboundAddress']) ? $values['InboundAddress'] : "";
        $this->InboundHookUrl = !empty($values['InboundHookUrl']) ? $values['InboundHookUrl'] : "";
        $this->BounceHookUrl = !empty($values['BounceHookUrl']) ? $values['BounceHookUrl'] : "";
        $this->OpenHookUrl = !empty($values['OpenHookUrl']) ? $values['OpenHookUrl'] : false;
        $this->PostFirstOpenOnly = !empty($values['PostFirstOpenOnly']) ? $values['PostFirstOpenOnly'] : false;

        $this->TrackOpens = !empty($values['TrackOpens']) ? $values['TrackOpens'] : "";
        $this->TrackLinks = !empty($values['TrackLinks']) ? $values['TrackLinks'] : "";
        $this->ClickHookUrl = !empty($values['ClickHookUrl']) ? $values['ClickHookUrl'] : "";
        $this->DeliveryHookUrl = !empty($values['DeliveryHookUrl']) ? $values['DeliveryHookUrl'] : "";
        $this->InboundDomain = !empty($values['InboundDomain']) ? $values['InboundDomain'] : "";
        $this->InboundHash = !empty($values['InboundHash']) ? $values['InboundHash'] : "";
        $this->InboundSpamThreshold = !empty($values['InboundSpamThreshold']) ? $values['InboundSpamThreshold'] : 0;
        $this->EnableSmtpApiErrorHooks = !empty($values['EnableSmtpApiErrorHooks']) ? $values['EnableSmtpApiErrorHooks'] : false;
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
     * @return PostmarkServer
     */
    public function setID(int $ID): PostmarkServer
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
     * @return PostmarkServer
     */
    public function setName(string $Name): PostmarkServer
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return array
     */
    public function getApiTokens(): array
    {
        return $this->ApiTokens;
    }

    /**
     * @param array $ApiTokens
     * @return PostmarkServer
     */
    public function setApiTokens(array $ApiTokens): PostmarkServer
    {
        $this->ApiTokens = $ApiTokens;
        return $this;
    }

    /**
     * @return string
     */
    public function getServerLink(): string
    {
        return $this->ServerLink;
    }

    /**
     * @param string $ServerLink
     * @return PostmarkServer
     */
    public function setServerLink(string $ServerLink): PostmarkServer
    {
        $this->ServerLink = $ServerLink;
        return $this;
    }

    /**
     * @return string
     */
    public function getColor(): string
    {
        return $this->Color;
    }

    /**
     * @param string $Color
     * @return PostmarkServer
     */
    public function setColor(string $Color): PostmarkServer
    {
        $this->Color = $Color;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSmtpApiActivated(): bool
    {
        return $this->SmtpApiActivated;
    }

    /**
     * @param bool $SmtpApiActivated
     * @return PostmarkServer
     */
    public function setSmtpApiActivated(bool $SmtpApiActivated): PostmarkServer
    {
        $this->SmtpApiActivated = $SmtpApiActivated;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRawEmailEnabled(): bool
    {
        return $this->RawEmailEnabled;
    }

    /**
     * @param bool $RawEmailEnabled
     * @return PostmarkServer
     */
    public function setRawEmailEnabled(bool $RawEmailEnabled): PostmarkServer
    {
        $this->RawEmailEnabled = $RawEmailEnabled;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryType(): string
    {
        return $this->DeliveryType;
    }

    /**
     * @param string $DeliveryType
     * @return PostmarkServer
     */
    public function setDeliveryType(string $DeliveryType): PostmarkServer
    {
        $this->DeliveryType = $DeliveryType;
        return $this;
    }

    /**
     * @return string
     */
    public function getInboundAddress(): string
    {
        return $this->InboundAddress;
    }

    /**
     * @param string $InboundAddress
     * @return PostmarkServer
     */
    public function setInboundAddress(string $InboundAddress): PostmarkServer
    {
        $this->InboundAddress = $InboundAddress;
        return $this;
    }

    /**
     * @return string
     */
    public function getInboundHookUrl(): string
    {
        return $this->InboundHookUrl;
    }

    /**
     * @param string $InboundHookUrl
     * @return PostmarkServer
     */
    public function setInboundHookUrl(string $InboundHookUrl): PostmarkServer
    {
        $this->InboundHookUrl = $InboundHookUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getBounceHookUrl(): string
    {
        return $this->BounceHookUrl;
    }

    /**
     * @param string $BounceHookUrl
     * @return PostmarkServer
     */
    public function setBounceHookUrl(string $BounceHookUrl): PostmarkServer
    {
        $this->BounceHookUrl = $BounceHookUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getOpenHookUrl(): string
    {
        return $this->OpenHookUrl;
    }

    /**
     * @param string $OpenHookUrl
     * @return PostmarkServer
     */
    public function setOpenHookUrl(string $OpenHookUrl): PostmarkServer
    {
        $this->OpenHookUrl = $OpenHookUrl;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPostFirstOpenOnly(): bool
    {
        return $this->PostFirstOpenOnly;
    }

    /**
     * @param bool $PostFirstOpenOnly
     * @return PostmarkServer
     */
    public function setPostFirstOpenOnly(bool $PostFirstOpenOnly): PostmarkServer
    {
        $this->PostFirstOpenOnly = $PostFirstOpenOnly;
        return $this;
    }

    /**
     * @return bool
     */
    public function isTrackOpens(): bool
    {
        return $this->TrackOpens;
    }

    /**
     * @param bool $TrackOpens
     * @return PostmarkServer
     */
    public function setTrackOpens(bool $TrackOpens): PostmarkServer
    {
        $this->TrackOpens = $TrackOpens;
        return $this;
    }

    /**
     * @return string
     */
    public function getTrackLinks(): string
    {
        return $this->TrackLinks;
    }

    /**
     * @param string $TrackLinks
     * @return PostmarkServer
     */
    public function setTrackLinks(string $TrackLinks): PostmarkServer
    {
        $this->TrackLinks = $TrackLinks;
        return $this;
    }

    /**
     * @return string
     */
    public function getClickHookUrl(): string
    {
        return $this->ClickHookUrl;
    }

    /**
     * @param string $ClickHookUrl
     * @return PostmarkServer
     */
    public function setClickHookUrl(string $ClickHookUrl): PostmarkServer
    {
        $this->ClickHookUrl = $ClickHookUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getDeliveryHookUrl(): string
    {
        return $this->DeliveryHookUrl;
    }

    /**
     * @param string $DeliveryHookUrl
     * @return PostmarkServer
     */
    public function setDeliveryHookUrl(string $DeliveryHookUrl): PostmarkServer
    {
        $this->DeliveryHookUrl = $DeliveryHookUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getInboundDomain(): string
    {
        return $this->InboundDomain;
    }

    /**
     * @param string $InboundDomain
     * @return PostmarkServer
     */
    public function setInboundDomain(string $InboundDomain): PostmarkServer
    {
        $this->InboundDomain = $InboundDomain;
        return $this;
    }

    /**
     * @return string
     */
    public function getInboundHash(): string
    {
        return $this->InboundHash;
    }

    /**
     * @param string $InboundHash
     * @return PostmarkServer
     */
    public function setInboundHash(string $InboundHash): PostmarkServer
    {
        $this->InboundHash = $InboundHash;
        return $this;
    }

    /**
     * @return int
     */
    public function getInboundSpamThreshold(): int
    {
        return $this->InboundSpamThreshold;
    }

    /**
     * @param int $InboundSpamThreshold
     * @return PostmarkServer
     */
    public function setInboundSpamThreshold(int $InboundSpamThreshold): PostmarkServer
    {
        $this->InboundSpamThreshold = $InboundSpamThreshold;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnableSmtpApiErrorHooks(): bool
    {
        return $this->EnableSmtpApiErrorHooks;
    }

    /**
     * @param bool $EnableSmtpApiErrorHooks
     * @return PostmarkServer
     */
    public function setEnableSmtpApiErrorHooks(bool $EnableSmtpApiErrorHooks): PostmarkServer
    {
        $this->EnableSmtpApiErrorHooks = $EnableSmtpApiErrorHooks;
        return $this;
    }

}