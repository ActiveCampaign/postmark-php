<?php
/**
 * This is a list of Postmark Servers (think organization rather than computer servers)
 * php version 8.1
 * @cate
 */
namespace Postmark\Models;

/**
 *
 */
class PostmarkServerList
{
    /**
     * @var int
     */
    public int $TotalCount;
    /**
     * @var array
     */
    public array $Servers;

    /**
     * @param array $values
     */
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

    /**
     * @return array
     */
    public function getServers(): array
    {
        return $this->Servers;
    }

    /**
     * @param array $Servers
     * @return $this
     */
    public function setServers(array $Servers): PostmarkServerList
    {
        $this->Servers = $Servers;

        return $this;
    }
}
