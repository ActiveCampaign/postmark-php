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
        $this->Name = !empty($values['Name']) ? $values['Name'] : '';
        $this->ApiTokens = !empty($values['ApiTokens']) ? $values['ApiTokens'] : [];
        $this->ServerLink = !empty($values['ServerLink']) ? $values['ServerLink'] : '';
        $this->Color = !empty($values['Color']) ? $values['Color'] : '';

        $this->SmtpApiActivated = !empty($values['SmtpApiActivated']) ? $values['SmtpApiActivated'] : false;
        $this->RawEmailEnabled = !empty($values['RawEmailEnabled']) ? $values['RawEmailEnabled'] : false;
        $this->DeliveryType = !empty($values['DeliveryType']) ? $values['DeliveryType'] : '';
        $this->InboundAddress = !empty($values['InboundAddress']) ? $values['InboundAddress'] : '';
        $this->InboundHookUrl = !empty($values['InboundHookUrl']) ? $values['InboundHookUrl'] : '';
        $this->BounceHookUrl = !empty($values['BounceHookUrl']) ? $values['BounceHookUrl'] : '';
        $this->OpenHookUrl = !empty($values['OpenHookUrl']) ? $values['OpenHookUrl'] : false;
        $this->PostFirstOpenOnly = !empty($values['PostFirstOpenOnly']) ? $values['PostFirstOpenOnly'] : false;

        $this->TrackOpens = !empty($values['TrackOpens']) ? $values['TrackOpens'] : '';
        $this->TrackLinks = !empty($values['TrackLinks']) ? $values['TrackLinks'] : '';
        $this->ClickHookUrl = !empty($values['ClickHookUrl']) ? $values['ClickHookUrl'] : '';
        $this->DeliveryHookUrl = !empty($values['DeliveryHookUrl']) ? $values['DeliveryHookUrl'] : '';
        $this->InboundDomain = !empty($values['InboundDomain']) ? $values['InboundDomain'] : '';
        $this->InboundHash = !empty($values['InboundHash']) ? $values['InboundHash'] : '';
        $this->InboundSpamThreshold = !empty($values['InboundSpamThreshold']) ? $values['InboundSpamThreshold'] : 0;
        $this->EnableSmtpApiErrorHooks = !empty($values['EnableSmtpApiErrorHooks']) ? $values['EnableSmtpApiErrorHooks'] : false;
    }

    public function getID(): int
    {
        return $this->ID;
    }

    public function setID(int $ID): PostmarkServer
    {
        $this->ID = $ID;

        return $this;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): PostmarkServer
    {
        $this->Name = $Name;

        return $this;
    }

    public function getApiTokens(): array
    {
        return $this->ApiTokens;
    }

    public function setApiTokens(array $ApiTokens): PostmarkServer
    {
        $this->ApiTokens = $ApiTokens;

        return $this;
    }

    public function getServerLink(): string
    {
        return $this->ServerLink;
    }

    public function setServerLink(string $ServerLink): PostmarkServer
    {
        $this->ServerLink = $ServerLink;

        return $this;
    }

    public function getColor(): string
    {
        return $this->Color;
    }

    public function setColor(string $Color): PostmarkServer
    {
        $this->Color = $Color;

        return $this;
    }

    public function isSmtpApiActivated(): bool
    {
        return $this->SmtpApiActivated;
    }

    public function setSmtpApiActivated(bool $SmtpApiActivated): PostmarkServer
    {
        $this->SmtpApiActivated = $SmtpApiActivated;

        return $this;
    }

    public function isRawEmailEnabled(): bool
    {
        return $this->RawEmailEnabled;
    }

    public function setRawEmailEnabled(bool $RawEmailEnabled): PostmarkServer
    {
        $this->RawEmailEnabled = $RawEmailEnabled;

        return $this;
    }

    public function getDeliveryType(): string
    {
        return $this->DeliveryType;
    }

    public function setDeliveryType(string $DeliveryType): PostmarkServer
    {
        $this->DeliveryType = $DeliveryType;

        return $this;
    }

    public function getInboundAddress(): string
    {
        return $this->InboundAddress;
    }

    public function setInboundAddress(string $InboundAddress): PostmarkServer
    {
        $this->InboundAddress = $InboundAddress;

        return $this;
    }

    public function getInboundHookUrl(): string
    {
        return $this->InboundHookUrl;
    }

    public function setInboundHookUrl(string $InboundHookUrl): PostmarkServer
    {
        $this->InboundHookUrl = $InboundHookUrl;

        return $this;
    }

    public function getBounceHookUrl(): string
    {
        return $this->BounceHookUrl;
    }

    public function setBounceHookUrl(string $BounceHookUrl): PostmarkServer
    {
        $this->BounceHookUrl = $BounceHookUrl;

        return $this;
    }

    public function getOpenHookUrl(): string
    {
        return $this->OpenHookUrl;
    }

    public function setOpenHookUrl(string $OpenHookUrl): PostmarkServer
    {
        $this->OpenHookUrl = $OpenHookUrl;

        return $this;
    }

    public function isPostFirstOpenOnly(): bool
    {
        return $this->PostFirstOpenOnly;
    }

    public function setPostFirstOpenOnly(bool $PostFirstOpenOnly): PostmarkServer
    {
        $this->PostFirstOpenOnly = $PostFirstOpenOnly;

        return $this;
    }

    public function isTrackOpens(): bool
    {
        return $this->TrackOpens;
    }

    public function setTrackOpens(bool $TrackOpens): PostmarkServer
    {
        $this->TrackOpens = $TrackOpens;

        return $this;
    }

    public function getTrackLinks(): string
    {
        return $this->TrackLinks;
    }

    public function setTrackLinks(string $TrackLinks): PostmarkServer
    {
        $this->TrackLinks = $TrackLinks;

        return $this;
    }

    public function getClickHookUrl(): string
    {
        return $this->ClickHookUrl;
    }

    public function setClickHookUrl(string $ClickHookUrl): PostmarkServer
    {
        $this->ClickHookUrl = $ClickHookUrl;

        return $this;
    }

    public function getDeliveryHookUrl(): string
    {
        return $this->DeliveryHookUrl;
    }

    public function setDeliveryHookUrl(string $DeliveryHookUrl): PostmarkServer
    {
        $this->DeliveryHookUrl = $DeliveryHookUrl;

        return $this;
    }

    public function getInboundDomain(): string
    {
        return $this->InboundDomain;
    }

    public function setInboundDomain(string $InboundDomain): PostmarkServer
    {
        $this->InboundDomain = $InboundDomain;

        return $this;
    }

    public function getInboundHash(): string
    {
        return $this->InboundHash;
    }

    public function setInboundHash(string $InboundHash): PostmarkServer
    {
        $this->InboundHash = $InboundHash;

        return $this;
    }

    public function getInboundSpamThreshold(): int
    {
        return $this->InboundSpamThreshold;
    }

    public function setInboundSpamThreshold(int $InboundSpamThreshold): PostmarkServer
    {
        $this->InboundSpamThreshold = $InboundSpamThreshold;

        return $this;
    }

    public function isEnableSmtpApiErrorHooks(): bool
    {
        return $this->EnableSmtpApiErrorHooks;
    }

    public function setEnableSmtpApiErrorHooks(bool $EnableSmtpApiErrorHooks): PostmarkServer
    {
        $this->EnableSmtpApiErrorHooks = $EnableSmtpApiErrorHooks;

        return $this;
    }
}
