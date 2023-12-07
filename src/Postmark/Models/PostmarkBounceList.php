<?php

namespace Postmark\Models;

class PostmarkBounceList
{
    public int $TotalCount;
    public array $Bounces;

    public function __construct(array $values)
    {
        $this->TotalCount = !empty($values['TotalCount']) ? $values['TotalCount'] : 0;
        $tempBounce = [];
        foreach ($values['Bounces'] as $bounce) {
            $obj = json_decode(json_encode($bounce));
            $postmarkBounce = new PostmarkBounce((array) $obj);

            $tempBounce[] = $postmarkBounce;
        }
        $this->Bounces = $tempBounce;
    }

    public function getTotalCount(): int
    {
        return $this->TotalCount;
    }

    public function setTotalCount(int $TotalCount): PostmarkBounceList
    {
        $this->TotalCount = $TotalCount;

        return $this;
    }

    public function getBounces(): array
    {
        return $this->Bounces;
    }

    public function setBounces(array $Bounces): PostmarkBounceList
    {
        $this->Bounces = $Bounces;

        return $this;
    }
}
