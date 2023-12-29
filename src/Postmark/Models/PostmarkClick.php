<?php

namespace Postmark\Models;

class PostmarkClick
{
    public bool $OriginalLink;
    public string $MessageID;
    public string $UserAgent;
    public ?PostmarkGeographyInfo $Geo;
    public string $Platform;
    public ?PostmarkAgentInfo $Client;
    public ?PostmarkAgentInfo $OS;

    public function __construct(array $values)
    {
        $this->OriginalLink = !empty($values['OriginalLink']) ? $values['OriginalLink'] : false;
        $this->MessageID = !empty($values['MessageID']) ? $values['MessageID'] : '';
        $this->UserAgent = !empty($values['UserAgent']) ? $values['UserAgent'] : '';
        !empty($values['Geo']) ? $this->setGeo($values['Geo']) : $this->setGeo(null);
        $this->Platform = !empty($values['Platform']) ? $values['Platform'] : '';
        !empty($values['Client']) ? $this->setClient($values['Client']) : $this->setClient(null);
        !empty($values['OS']) ? $this->setOS($values['OS']) : $this->setOS(null);
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
     */
    public function setUserAgent(mixed $UserAgent): PostmarkClick
    {
        $this->UserAgent = $UserAgent;

        return $this;
    }

    /**
     * @return null|mixed|PostmarkGeographyInfo
     */
    public function getGeo(): mixed
    {
        return $this->Geo;
    }

    public function setGeo(mixed $Geo): PostmarkClick
    {
        if (is_object($Geo)) {
            $Geo = new PostmarkGeographyInfo((array) $Geo);
        }
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
     */
    public function setPlatform(mixed $Platform): PostmarkClick
    {
        $this->Platform = $Platform;

        return $this;
    }

    public function getClient(): mixed
    {
        return $this->Client;
    }

    /**
     * @param null|mixed|PostmarkAgentInfo $Client
     */
    public function setClient(mixed $Client): PostmarkClick
    {
        if (is_object($Client)) {
            $Client = new PostmarkAgentInfo((array) $Client);
        }
        $this->Client = $Client;

        return $this;
    }

    /**
     * @return null|mixed|PostmarkAgentInfo
     */
    public function getOS(): mixed
    {
        return $this->OS;
    }

    /**
     * @param null|mixed|PostmarkAgentInfo $OS
     */
    public function setOS(mixed $OS): PostmarkClick
    {
        if (is_object($OS)) {
            $OS = new PostmarkAgentInfo((array) $OS);
        }
        $this->OS = $OS;

        return $this;
    }
}
