<?php

namespace Postmark\Models;

class PostmarkGeographyInfo
{
    public string $City;
    public string $Region;
    public string $Country;
    public string $IP;
    public string $RegionISOCode;
    public string $CountryISOCode;
    public string $Coords;

    public function __construct(array $values)
    {
        $this->City = !empty($values['City']) ? $values['City'] : '';
        $this->Region = !empty($values['Region']) ? $values['Region'] : '';
        $this->Country = !empty($values['Country']) ? $values['Country'] : '';
        $this->IP = !empty($values['IP']) ? $values['IP'] : '';
        $this->RegionISOCode = !empty($values['RegionISOCode']) ? $values['RegionISOCode'] : '';
        $this->CountryISOCode = !empty($values['CountryISOCode']) ? $values['CountryISOCode'] : '';
        $this->Coords = !empty($values['Coords']) ? $values['Coords'] : '';
    }

    public function getCity(): string
    {
        return $this->City;
    }

    public function setCity(string $City): PostmarkGeographyInfo
    {
        $this->City = $City;

        return $this;
    }

    public function getRegion(): string
    {
        return $this->Region;
    }

    public function setRegion(string $Region): PostmarkGeographyInfo
    {
        $this->Region = $Region;

        return $this;
    }

    public function getCountry(): string
    {
        return $this->Country;
    }

    public function setCountry(string $Country): PostmarkGeographyInfo
    {
        $this->Country = $Country;

        return $this;
    }

    public function getIP(): string
    {
        return $this->IP;
    }

    public function setIP(string $IP): PostmarkGeographyInfo
    {
        $this->IP = $IP;

        return $this;
    }

    public function getRegionISOCode(): string
    {
        return $this->RegionISOCode;
    }

    public function setRegionISOCode(string $RegionISOCode): PostmarkGeographyInfo
    {
        $this->RegionISOCode = $RegionISOCode;

        return $this;
    }

    public function getCountryISOCode(): string
    {
        return $this->CountryISOCode;
    }

    public function setCountryISOCode(string $CountryISOCode): PostmarkGeographyInfo
    {
        $this->CountryISOCode = $CountryISOCode;

        return $this;
    }

    public function getCoords(): string
    {
        return $this->Coords;
    }

    public function setCoords(string $Coords): PostmarkGeographyInfo
    {
        $this->Coords = $Coords;

        return $this;
    }
}
