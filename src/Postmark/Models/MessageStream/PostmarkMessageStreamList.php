<?php

namespace Postmark\Models\MessageStream;

use Postmark\Models\MessageStream\PostmarkMessageStream;

class PostmarkMessageStreamList
{
    public int $TotalCount;
    public array $MessageStreams;

    public function __construct(array $values)
    {
        $this->TotalCount = !empty($values['TotalCount']) ? $values['TotalCount'] : 0;
        $tempMessageStreams = array();
        foreach ($values['MessageStreams'] as $open) {
            $obj = json_decode(json_encode($open));
            $postmarkMessageStreams = new PostmarkMessageStream((array)$obj);

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

    /**
     * @param int $TotalCount
     * @return PostmarkMessageStreamList
     */
    public function setTotalCount(int $TotalCount): PostmarkMessageStreamList
    {
        $this->TotalCount = $TotalCount;
        return $this;
    }

    /**
     * @return array
     */
    public function getMessageStreams(): array
    {
        return $this->MessageStreams;
    }

    /**
     * @param array $MessageStreams
     * @return PostmarkMessageStreamList
     */
    public function setMessageStreams(array $MessageStreams): PostmarkMessageStreamList
    {
        $this->MessageStreams = $MessageStreams;
        return $this;
    }

}