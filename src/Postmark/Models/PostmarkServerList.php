<?php

namespace Postmark\Models;

class PostmarkServerList
{
    public int $TotalCount;
    public array $Servers;

    public function __construct(array $values)
    {
        $this->TotalCount = !empty($values['TotalCount']) ? $values['TotalCount'] : 0;
        $tempServers = [];
        foreach ($values['Servers'] as $server) {
            $obj = json_decode(json_encode($server));
            $postmarkServer = new PostmarkServer((array) $obj);

            $tempServers[] = $postmarkServer;
        }
        $this->Servers = $tempServers;
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
    public function setTotalCount(mixed $TotalCount): PostmarkServerList
    {
        $this->TotalCount = $TotalCount;

        return $this;
    }

    public function getServers(): array
    {
        return $this->Servers;
    }

    public function setServers(array $Servers): PostmarkServerList
    {
        $this->Servers = $Servers;

        return $this;
    }
}
