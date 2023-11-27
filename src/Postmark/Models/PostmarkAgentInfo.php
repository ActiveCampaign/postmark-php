<?php

namespace Postmark\Models;

class PostmarkAgentInfo
{
    public string $Name;
    public string $Company;
    public string $Family;

    public function __construct(array $values)
    {
        $this->Name = !empty($values['Name']) ? $values['Name'] : '';
        $this->Company = !empty($values['Company']) ? $values['Company'] : '';
        $this->Family = !empty($values['Family']) ? $values['Family'] : '';
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): PostmarkAgentInfo
    {
        $this->Name = $Name;

        return $this;
    }

    public function getCompany(): string
    {
        return $this->Company;
    }

    public function setCompany(string $Company): PostmarkAgentInfo
    {
        $this->Company = $Company;

        return $this;
    }

    public function getFamily(): string
    {
        return $this->Family;
    }

    public function setFamily(string $Family): PostmarkAgentInfo
    {
        $this->Family = $Family;

        return $this;
    }
}
