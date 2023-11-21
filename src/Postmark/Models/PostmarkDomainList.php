<?php

namespace Postmark\Models;

use Postmark\Models\PostmarkDomain;

class PostmarkDomainList
{
    public int $TotalCount;
    public array $Domains;

    public function __construct(array $values)
    {
        $this->TotalCount = !empty($values['TotalCount']) ? $values['TotalCount'] : 0;
        $tempDomains = array();
        foreach ($values['Domains'] as $domain)
        {
            $obj = json_decode(json_encode($domain));
            $postmarkDomain = new PostmarkDomain((array)$obj);

            $tempDomains[] = $postmarkDomain;
        }
        $this->Domains = $tempDomains;
    }


    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->TotalCount;
    }

    /**
     * @param int $TotalCount
     * @return PostmarkDomainList
     */
    public function setTotalCount(int $TotalCount): PostmarkDomainList
    {
        $this->TotalCount = $TotalCount;
        return $this;
    }

    /**
     * @return array
     */
    public function getDomains(): array
    {
        return $this->Domains;
    }

    /**
     * @param array $Domains
     * @return PostmarkDomainList
     */
    public function setDomains(array $Domains): PostmarkDomainList
    {
        $this->Domains = $Domains;
        return $this;
    }


}