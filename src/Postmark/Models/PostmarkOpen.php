<?php

namespace Postmark\Models;

class PostmarkOpen
{
    public bool $FirstOpen;
    public string $MessageID;
    public string $UserAgent;
    public ?PostmarkGeographyInfo $Geo;
    public string $Platform;
    public int $ReadSeconds;
    public string $ReceivedAt;
    public ?PostmarkAgentInfo $Client;
    public ?PostmarkAgentInfo $OS;

    public function __construct(array $values)
    {
        $this->FirstOpen = !empty($values['FirstOpen']) ? $values['FirstOpen'] : '';
        $this->MessageID = !empty($values['MessageID']) ? $values['MessageID'] : '';
        $this->UserAgent = !empty($values['UserAgent']) ? $values['UserAgent'] : '';
        !empty($values['Geo']) ? $this->setGeo($values['Geo']) : $this->setGeo(null);
        $this->Platform = !empty($values['Platform']) ? $values['Platform'] : '';
        $this->ReadSeconds = !empty($values['ReadSeconds']) ? $values['ReadSeconds'] : 0;
        $this->ReceivedAt = !empty($values['ReceivedAt']) ? $values['ReceivedAt'] : '';
        !empty($values['Client']) ? $this->setClient($values['Client']) : $this->setClient(null);
        !empty($values['OS']) ? $this->setOS($values['OS']) : $this->setOS(null);
    }

    public function isFirstOpen(): bool
    {
        return $this->FirstOpen;
    }

    public function setFirstOpen(bool $FirstOpen): PostmarkOpen
    {
        $this->FirstOpen = $FirstOpen;

        return $this;
    }

    public function getMessageID(): string
    {
        return $this->MessageID;
    }

    public function setMessageID(string $MessageID): PostmarkOpen
    {
        $this->MessageID = $MessageID;

        return $this;
    }

    public function getUserAgent(): string
    {
        return $this->UserAgent;
    }

    public function setUserAgent(string $UserAgent): PostmarkOpen
    {
        $this->UserAgent = $UserAgent;

        return $this;
    }

    public function getGeo(): PostmarkGeographyInfo
    {
        return $this->Geo;
    }

    public function setGeo(mixed $Geo): PostmarkOpen
    {
        if (is_object($Geo)) {
            $Geo = new PostmarkGeographyInfo((array) $Geo);
        }
        $this->Geo = $Geo;

        return $this;
    }

    public function getPlatform(): string
    {
        return $this->Platform;
    }

    public function setPlatform(string $Platform): PostmarkOpen
    {
        $this->Platform = $Platform;

        return $this;
    }

    public function getReadSeconds(): int
    {
        return $this->ReadSeconds;
    }

    public function setReadSeconds(int $ReadSeconds): PostmarkOpen
    {
        $this->ReadSeconds = $ReadSeconds;

        return $this;
    }

    public function getReceivedAt(): string
    {
        return $this->ReceivedAt;
    }

    public function setReceivedAt(string $ReceivedAt): PostmarkOpen
    {
        $this->ReceivedAt = $ReceivedAt;

        return $this;
    }

    public function getClient(): PostmarkAgentInfo
    {
        return $this->Client;
    }

    public function setClient(mixed $Client): PostmarkOpen
    {
        if (is_object($Client)) {
            $Client = new PostmarkAgentInfo((array) $Client);
        }
        $this->Client = $Client;

        return $this;
    }

    public function getOS(): PostmarkAgentInfo
    {
        return $this->OS;
    }

    public function setOS(mixed $OS): PostmarkOpen
    {
        if (is_object($OS)) {
            $OS = new PostmarkAgentInfo((array) $OS);
        }
        $this->OS = $OS;

        return $this;
    }
}
