<?php

declare(strict_types=1);

namespace Postmark\Models\Webhooks;

use JsonSerializable;

/**
 * Model describing Basic HTTP Authentication.
 */
class HttpAuth implements JsonSerializable
{
    private ?string $username;
    private ?string $password;

    /**
     * Create a new HttpAuth.
     *
     * @param string|null $username Username to use.
     * @param string|null $password Password to use.
     */
    public function __construct(?string $username = null, ?string $password = null)
    {
        $this->username = $username;
        $this->password = $password;
    }

    /** @return array{Username: string|null, Password: string|null} */
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
