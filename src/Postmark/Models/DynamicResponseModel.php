<?php

declare(strict_types=1);

namespace Postmark\Models;

use function is_array;

/**
 * The DynamicResponseModel class allows flexible and forgiving access to responses from the Postmark API.
 * Most responses from the PostmarkClient return a DynamicResponseModel, so understanding how it works is useful.
 * Essentially, you can use either object or array index notation to lookup values. The member names are case
 * insensitive, so that each of these are acceptable ways of accessing "id" on a server response, for example:
 * ```
 * //Create a client instance and get server info.
 * $client = new PostmarkClient($server_token);
 * $server = $client->getServer();
 * //You have many ways of accessing the same members:
 * $server->id;
 * $server->Id;
 * $server["id"];
 * $server["ID"];
 * ```
 */
final class DynamicResponseModel extends CaseInsensitiveArray
{
    /**
     * Convert array values to DynamicResponseModel instances
     *
     * @param string $name Name of element to get from the dynamic response model.
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        /** @psalm-var mixed|null $value */
        $value = $this[$name];

        return is_array($value) ? new self($value) : $value;
    }

    /**
     * Convert array values to DynamicResponseModel instances
     *
     * @param array-key $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        /** @psalm-var mixed|null $value */
        $value = parent::offsetGet($offset);

        return is_array($value) ? new self($value) : $value;
    }
}
