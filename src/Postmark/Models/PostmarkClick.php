<?php

namespace Postmark\Models;

use Postmark\Models\PostmarkGeographyInfo;
use Postmark\Models\PostmarkAgentInfo;

class PostmarkClick
{
    public bool $OriginalLink;
    public string $MessageID;
    public string $UserAgent;
    public ?PostmarkGeographyInfo $Geo;
    public string $Platform;
    public ?PostmarkAgentInfo $Client;
    public ?PostmarkAgentInfo $OS;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->OriginalLink = !empty($values['OriginalLink']) ? $values['OriginalLink'] : "";
        $this->MessageID = !empty($values['MessageID']) ? $values['MessageID'] : "";
        $this->UserAgent = !empty($values['UserAgent']) ? $values['UserAgent'] : "";
        $this->Geo = !empty($values['Geo']) ? $values['Geo'] : null;
        $this->Platform = !empty($values['Platform']) ? $values['Platform'] : "";
        $this->Client = !empty($values['Client']) ? $values['Client'] : null;
        $this->OS = !empty($values['OS']) ? $values['OS'] : null;
    }

    /**
     * @return bool|mixed|string
     */
    public function getOriginalLink(): mixed
    {
        return $this->OriginalLink;
    }

    /**
     * @param bool|mixed|string $OriginalLink
     * @return PostmarkClick
     */
    public function setOriginalLink(mixed $OriginalLink): PostmarkClick
    {
        $this->OriginalLink = $OriginalLink;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getMessageID(): mixed
    {
        return $this->MessageID;
    }

    /**
     * @param mixed|string $MessageID
     * @return PostmarkClick
     */
    public function setMessageID(mixed $MessageID): PostmarkClick
    {
        $this->MessageID = $MessageID;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getUserAgent(): mixed
    {
        return $this->UserAgent;
    }

    /**
     * @param mixed|string $UserAgent
     * @return PostmarkClick
     */
    public function setUserAgent(mixed $UserAgent): PostmarkClick
    {
        $this->UserAgent = $UserAgent;
        return $this;
    }

    /**
     * @return mixed|\Postmark\Models\PostmarkGeographyInfo|null
     */
    public function getGeo(): mixed
    {
        return $this->Geo;
    }

    /**
     * @param mixed|\Postmark\Models\PostmarkGeographyInfo|null $Geo
     * @return PostmarkClick
     */
    public function setGeo(mixed $Geo): PostmarkClick
    {
        $this->Geo = $Geo;
        return $this;
    }

    /**
     * @return mixed|string
     */
    public function getPlatform(): mixed
    {
        return $this->Platform;
    }

    /**
     * @param mixed|string $Platform
     * @return PostmarkClick
     */
    public function setPlatform(mixed $Platform): PostmarkClick
    {
        $this->Platform = $Platform;
        return $this;
    }

    /**
     * @return mixed|\Postmark\Models\PostmarkAgentInfo|null
     */
    public function getClient(): mixed
    {
        return $this->Client;
    }

    /**
     * @param mixed|\Postmark\Models\PostmarkAgentInfo|null $Client
     * @return PostmarkClick
     */
    public function setClient(mixed $Client): PostmarkClick
    {
        $this->Client = $Client;
        return $this;
    }

    /**
     * @return mixed|\Postmark\Models\PostmarkAgentInfo|null
     */
    public function getOS(): mixed
    {
        return $this->OS;
    }

    /**
     * @param mixed|\Postmark\Models\PostmarkAgentInfo|null $OS
     * @return PostmarkClick
     */
    public function setOS(mixed $OS): PostmarkClick
    {
        $this->OS = $OS;
        return $this;
    }

}