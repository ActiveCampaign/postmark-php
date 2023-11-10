<?php

namespace Postmark\Models;

use Postmark\Models\PostmarkGeographyInfo;
use Postmark\Models\PostmarkAgentInfo;

class PostmarkOpen
{
    public bool $FirstOpen;
    public string $MessageID;
    public string $UserAgent;
    public ?PostmarkGeographyInfo $Geo;
    public string $Platform;
    public int $ReadSeconds;
    public ?PostmarkAgentInfo $Client;
    public ?PostmarkAgentInfo $OS;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->FirstOpen = !empty($values['FirstOpen']) ? $values['FirstOpen'] : "";
        $this->MessageID = !empty($values['MessageID']) ? $values['MessageID'] : "";
        $this->UserAgent = !empty($values['UserAgent']) ? $values['UserAgent'] : "";
        $this->Geo = !empty($values['Geo']) ? $values['Geo'] : null;
        $this->Platform = !empty($values['Platform']) ? $values['Platform'] : "";
        $this->ReadSeconds = !empty($values['ReadSeconds']) ? $values['ReadSeconds'] : "";
        $this->Client = !empty($values['Client']) ? $values['Client'] : null;
        $this->OS = !empty($values['OS']) ? $values['OS'] : null;
    }

    /**
     * @return bool
     */
    public function isFirstOpen(): bool
    {
        return $this->FirstOpen;
    }

    /**
     * @param bool $FirstOpen
     * @return PostmarkOpen
     */
    public function setFirstOpen(bool $FirstOpen): PostmarkOpen
    {
        $this->FirstOpen = $FirstOpen;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessageID(): string
    {
        return $this->MessageID;
    }

    /**
     * @param string $MessageID
     * @return PostmarkOpen
     */
    public function setMessageID(string $MessageID): PostmarkOpen
    {
        $this->MessageID = $MessageID;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->UserAgent;
    }

    /**
     * @param string $UserAgent
     * @return PostmarkOpen
     */
    public function setUserAgent(string $UserAgent): PostmarkOpen
    {
        $this->UserAgent = $UserAgent;
        return $this;
    }

    /**
     * @return PostmarkGeographyInfo
     */
    public function getGeo(): PostmarkGeographyInfo
    {
        return $this->Geo;
    }

    /**
     * @param mixed $Geo
     * @return PostmarkOpen
     */
    public function setGeo(mixed $Geo): PostmarkOpen
    {
        if (is_object($Geo))
        {
            $Geo = new PostmarkGeographyInfo((array)$Geo);
        }
        $this->Geo = $Geo;
        return $this;
    }

    /**
     * @return string
     */
    public function getPlatform(): string
    {
        return $this->Platform;
    }

    /**
     * @param string $Platform
     * @return PostmarkOpen
     */
    public function setPlatform(string $Platform): PostmarkOpen
    {
        $this->Platform = $Platform;
        return $this;
    }

    /**
     * @return int
     */
    public function getReadSeconds(): int
    {
        return $this->ReadSeconds;
    }

    /**
     * @param int $ReadSeconds
     * @return PostmarkOpen
     */
    public function setReadSeconds(int $ReadSeconds): PostmarkOpen
    {
        $this->ReadSeconds = $ReadSeconds;
        return $this;
    }

    /**
     * @return PostmarkAgentInfo
     */
    public function getClient(): PostmarkAgentInfo
    {
        return $this->Client;
    }

    /**
     * @param mixed $Client
     * @return PostmarkOpen
     */
    public function setClient(mixed $Client): PostmarkOpen
    {
        if (is_object($Client))
        {
            $Client = new PostmarkAgentInfo((array)$Client);
        }
        $this->Client = $Client;
        return $this;
    }

    /**
     * @return PostmarkAgentInfo
     */
    public function getOS(): PostmarkAgentInfo
    {
        return $this->OS;
    }

    /**
     * @param mixed $OS
     * @return PostmarkOpen
     */
    public function setOS(mixed $OS): PostmarkOpen
    {
        if (is_object($OS))
        {
            $OS = new PostmarkAgentInfo((array)$OS);
        }
        $this->OS = $OS;
        return $this;
    }


}