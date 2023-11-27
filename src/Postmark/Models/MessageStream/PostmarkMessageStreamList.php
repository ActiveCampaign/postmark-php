<?php

namespace Postmark\Models\MessageStream;

class PostmarkMessageStreamList
{
    public int $TotalCount;
    public array $MessageStreams;

    public function __construct(array $values)
    {
        $this->TotalCount = !empty($values['TotalCount']) ? $values['TotalCount'] : 0;
        $tempMessageStreams = [];
        foreach ($values['MessageStreams'] as $open) {
            $obj = json_decode(json_encode($open));
            $postmarkMessageStreams = new PostmarkMessageStream((array) $obj);

            $tempMessageStreams[] = $postmarkMessageStreams;
        }
        $this->MessageStreams = $tempMessageStreams;
    }

    /**
     * @return int
     */
    public function getTotalCount(): mixed
    {
        return $this->TotalCount;
    }

    public function setTotalCount(int $TotalCount): PostmarkMessageStreamList
    {
        $this->TotalCount = $TotalCount;

        return $this;
    }

    public function getMessageStreams(): array
    {
        return $this->MessageStreams;
    }

    public function setMessageStreams(array $MessageStreams): PostmarkMessageStreamList
    {
        $this->MessageStreams = $MessageStreams;

        return $this;
    }
}
