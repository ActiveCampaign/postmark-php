<?php

namespace Postmark\Models;

use Postmark\Models\PostmarkClick;

class PostmarkClickList
{
    public int $TotalCount;
    public array $Clicks;

    public function __construct(array $values)
    {
        $this->TotalCount = !empty($values['TotalCount']) ? $values['TotalCount'] : 0;
        $tempClicks = array();
        foreach ($values['Click'] as $click) {
            $obj = json_decode(json_encode($click));
            $postmarkClick = new PostmarkClick((array)$obj);

            $tempClicks[] = $postmarkClick;
        }
        $this->Clicks = $tempClicks;
    }
}
