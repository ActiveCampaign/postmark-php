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

    /**
     * @return int|mixed
     */
    public function getTotalCount(): mixed
    {
        return $this->TotalCount;
    }

    /**
     * @param int|mixed $TotalCount
     * @return PostmarkOpenList
     */
    public function setTotalCount(mixed $TotalCount): PostmarkOpenList
    {
        $this->TotalCount = $TotalCount;
        return $this;
    }

    /**
     * @return array
     */
    public function getOpens(): array
    {
        return $this->Opens;
    }

    /**
     * @param array $Opens
     * @return PostmarkOpenList
     */
    public function setOpens(array $Opens): PostmarkOpenList
    {
        $this->Opens = $Opens;
        return $this;
    }

}
