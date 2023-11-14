<?php

namespace Postmark\Models;

use Postmark\Models\PostmarkBounce;

class PostmarkBounceList
{
    public int $TotalCount;
    public array $Bounces;

    public function __construct(array $values)
    {
        $this->TotalCount = !empty($values['TotalCount']) ? $values['TotalCount'] : 0;
        $tempBounce = array();
        foreach ($values['Bounces'] as $bounce) {
            $obj = json_decode(json_encode($bounce));
            $postmarkBounce = new PostmarkBounce((array)$obj);

            $tempBounce[] = $postmarkBounce;
        }
        $this->Bounces = $tempBounce;
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
     * @return PostmarkBounceList
     */
    public function setTotalCount(int $TotalCount): PostmarkBounceList
    {
        $this->TotalCount = $TotalCount;
        return $this;
    }

    /**
     * @return array
     */
    public function getBounces(): array
    {
        return $this->Bounces;
    }

    /**
     * @param array $Bounces
     * @return PostmarkBounceList
     */
    public function setBounces(array $Bounces): PostmarkBounceList
    {
        $this->Bounces = $Bounces;
        return $this;
    }

}