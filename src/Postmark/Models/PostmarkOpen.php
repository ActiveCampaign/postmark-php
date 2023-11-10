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
     * @return \Postmark\Models\PostmarkGeographyInfo
     */
    public function getGeo(): \Postmark\Models\PostmarkGeographyInfo
    {
        return $this->Geo;
    }

    /**
     * @param \Postmark\Models\PostmarkGeographyInfo $Geo
     * @return PostmarkOpen
     */
    public function setGeo(\Postmark\Models\PostmarkGeographyInfo $Geo): PostmarkOpen
    {
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
     * @return \Postmark\Models\PostmarkAgentInfo
     */
    public function getClient(): \Postmark\Models\PostmarkAgentInfo
    {
        return $this->Client;
    }

    /**
     * @param \Postmark\Models\PostmarkAgentInfo $Client
     * @return PostmarkOpen
     */
    public function setClient(\Postmark\Models\PostmarkAgentInfo $Client): PostmarkOpen
    {
        $this->Client = $Client;
        return $this;
    }

    /**
     * @return \Postmark\Models\PostmarkAgentInfo
     */
    public function getOS(): \Postmark\Models\PostmarkAgentInfo
    {
        return $this->OS;
    }

    /**
     * @param \Postmark\Models\PostmarkAgentInfo $OS
     * @return PostmarkOpen
     */
    public function setOS(\Postmark\Models\PostmarkAgentInfo $OS): PostmarkOpen
    {
        $this->OS = $OS;
        return $this;
    }


}