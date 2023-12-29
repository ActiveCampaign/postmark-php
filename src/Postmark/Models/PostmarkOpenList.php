<?php

namespace Postmark\Models;

class PostmarkOpenList
{
    public int $TotalCount;
    public array $Opens;

    public function __construct(array $values)
    {
        $this->TotalCount = !empty($values['TotalCount']) ? $values['TotalCount'] : 0;
        $tempOpens = [];
        foreach ($values['Opens'] as $open) {
            $obj = json_decode(json_encode($open));
            $postmarkOpen = new PostmarkOpen((array) $obj);

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
     */
    public function setTotalCount(mixed $TotalCount): PostmarkOpenList
    {
        $this->TotalCount = $TotalCount;

        return $this;
    }

    public function getOpens(): array
    {
        return $this->Opens;
    }

    public function setOpens(array $Opens): PostmarkOpenList
    {
        $this->Opens = $Opens;

        return $this;
    }
}
