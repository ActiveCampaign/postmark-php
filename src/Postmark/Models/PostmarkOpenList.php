<?php

namespace Postmark\Models;

use Postmark\Models\PostmarkOpen;

class PostmarkOpenList
{
    public int $TotalCount;
    public array $Opens;

    public function __construct(array $values)
    {
        $this->TotalCount = !empty($values['TotalCount']) ? $values['TotalCount'] : 0;
        $tempOpens = array();
        foreach ($values['Opens'] as $open) {
            $obj = json_decode(json_encode($open));
            $postmarkOpen = new PostmarkOpen((array)$obj);

            $tempOpens[] = $postmarkOpen;
        }
        $this->Opens = $tempOpens;
    }
}
