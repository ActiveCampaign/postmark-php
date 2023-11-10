<?php

namespace Postmark\Models;

class PostmarkAgentInfo
{
    public string $Name;
    public string $Company;
    public string $Family;

    /**
     * @param array $values
     */
    public function __construct(array $values)
    {
        $this->Name = !empty($values['Name']) ? $values['Name'] : "";
        $this->Company = !empty($values['Company']) ? $values['Company'] : "";
        $this->Family = !empty($values['Family']) ? $values['Family'] : "";
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
     * @return PostmarkAgentInfo
     */
    public function setName(string $Name): PostmarkAgentInfo
    {
        $this->Name = $Name;
        return $this;
    }

    /**
     * @return string
     */
    public function getCompany(): string
    {
        return $this->Company;
    }

    /**
     * @param string $Company
     * @return PostmarkAgentInfo
     */
    public function setCompany(string $Company): PostmarkAgentInfo
    {
        $this->Company = $Company;
        return $this;
    }

    /**
     * @return string
     */
    public function getFamily(): string
    {
        return $this->Family;
    }

    /**
     * @param string $Family
     * @return PostmarkAgentInfo
     */
    public function setFamily(string $Family): PostmarkAgentInfo
    {
        $this->Family = $Family;
        return $this;
    }


}