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

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->City = !empty($values['City']) ? $values['City'] : "";
        $this->Region = !empty($values['Region']) ? $values['Region'] : "";
        $this->Country = !empty($values['Country']) ? $values['Country'] : "";
        $this->IP = !empty($values['IP']) ? $values['IP'] : "";
        $this->RegionISOCode = !empty($values['RegionISOCode']) ? $values['RegionISOCode'] : "";
        $this->CountryISOCode = !empty($values['CountryISOCode']) ? $values['CountryISOCode'] : "";
        $this->Coords = !empty($values['Coords']) ? $values['Coords'] : "";
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->City;
    }

    /**
     * @param string $City
     * @return PostmarkGeographyInfo
     */
    public function setCity(string $City): PostmarkGeographyInfo
    {
        $this->City = $City;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegion(): string
    {
        return $this->Region;
    }

    /**
     * @param string $Region
     * @return PostmarkGeographyInfo
     */
    public function setRegion(string $Region): PostmarkGeographyInfo
    {
        $this->Region = $Region;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->Country;
    }

    /**
     * @param string $Country
     * @return PostmarkGeographyInfo
     */
    public function setCountry(string $Country): PostmarkGeographyInfo
    {
        $this->Country = $Country;
        return $this;
    }

    /**
     * @return string
     */
    public function getIP(): string
    {
        return $this->IP;
    }

    /**
     * @param string $IP
     * @return PostmarkGeographyInfo
     */
    public function setIP(string $IP): PostmarkGeographyInfo
    {
        $this->IP = $IP;
        return $this;
    }

    /**
     * @return string
     */
    public function getRegionISOCode(): string
    {
        return $this->RegionISOCode;
    }

    /**
     * @param string $RegionISOCode
     * @return PostmarkGeographyInfo
     */
    public function setRegionISOCode(string $RegionISOCode): PostmarkGeographyInfo
    {
        $this->RegionISOCode = $RegionISOCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryISOCode(): string
    {
        return $this->CountryISOCode;
    }

    /**
     * @param string $CountryISOCode
     * @return PostmarkGeographyInfo
     */
    public function setCountryISOCode(string $CountryISOCode): PostmarkGeographyInfo
    {
        $this->CountryISOCode = $CountryISOCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCoords(): string
    {
        return $this->Coords;
    }

    /**
     * @param string $Coords
     * @return PostmarkGeographyInfo
     */
    public function setCoords(string $Coords): PostmarkGeographyInfo
    {
        $this->Coords = $Coords;
        return $this;
    }


}