<?php

namespace Postmark\Models;

class PostmarkClickList
{
    public int $TotalCount;
    public array $Clicks;

    public function __construct(array $values)
    {
        $this->TotalCount = !empty($values['TotalCount']) ? $values['TotalCount'] : 0;
        $tempClicks = [];
        foreach ($values['Clicks'] as $click) {
            $obj = json_decode(json_encode($click));
            $postmarkClick = new PostmarkClick((array) $obj);

            $tempClicks[] = $postmarkClick;
        }
        $this->Clicks = $tempClicks;
    }

    /**
     * @return int|mixed
     */
    public function getTotalCount(): mixed
    {
        return $this->TotalCount;
    }

    /**
     * @param int|mixed $TotalCount
     */
    public function setTotalCount(mixed $TotalCount): PostmarkClickList
    {
        $this->TotalCount = $TotalCount;

        return $this;
    }

    public function getClicks(): array
    {
        return $this->Clicks;
    }

    public function setClicks(array $Clicks): PostmarkClickList
    {
        $this->Clicks = $Clicks;

        return $this;
    }
}
