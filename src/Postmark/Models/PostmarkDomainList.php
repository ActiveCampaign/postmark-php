<?php

namespace Postmark\Models;

class PostmarkDomainList
{
    public int $TotalCount;
    public array $Domains;

    public function __construct(array $values)
    {
        $this->TotalCount = !empty($values['TotalCount']) ? $values['TotalCount'] : 0;
        $tempDomains = [];
        foreach ($values['Domains'] as $domain) {
            $obj = json_decode(json_encode($domain));
            $postmarkDomain = new PostmarkDomain((array) $obj);

            $tempDomains[] = $postmarkDomain;
        }
        $this->Domains = $tempDomains;
    }

    public function getTotalCount(): int
    {
        return $this->TotalCount;
    }

    public function setTotalCount(int $TotalCount): PostmarkDomainList
    {
        $this->TotalCount = $TotalCount;

        return $this;
    }

    public function getDomains(): array
    {
        return $this->Domains;
    }

    public function setDomains(array $Domains): PostmarkDomainList
    {
        $this->Domains = $Domains;

        return $this;
    }
}
