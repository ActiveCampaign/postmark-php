<?php

declare(strict_types=1);

namespace Postmark\Models\Webhooks;

use JsonSerializable;

/**
 * Model describing Basic HTTP Authentication.
 */
class HttpAuth implements JsonSerializable
{
    /**
     * Create a new HttpAuth.
     *
     * @param string|null $username Username to use.
     * @param string|null $password Password to use.
     */
    public function __construct(private string|null $username = null, private string|null $password = null)
    {
    }

    /** @return array{Username: string|null, Password: string|null} */
    public function jsonSerialize(): array
    {
        return [
            'Username' => $this->username,
            'Password' => $this->password,
        ];
    }

    public function getUsername(): string|null
    {
        return $this->username;
    }

    public function getPassword(): string|null
    {
        return $this->password;
    }
}
