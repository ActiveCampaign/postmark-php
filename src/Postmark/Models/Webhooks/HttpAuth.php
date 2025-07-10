<?php

namespace Postmark\Models\Webhooks;

use JsonSerializable;

/**
 * Model describing Basic HTTP Authentication.
 */
class HttpAuth implements JsonSerializable
{
    private $username;
    private $password;

    /**
     * Create a new HttpAuth.
     *
     * @param string $username username to use
     * @param string $password password to use
     */
    public function __construct(?string $username = null, ?string $password = null)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function jsonSerialize(): array
    {
        return [
            'Username' => $this->username,
            'Password' => $this->password,
        ];
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
