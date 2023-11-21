<?php
namespace Postmark\Models\Webhooks;

/**
 * Model describing Basic HTTP Authentication.
 */
class HttpAuth implements \JsonSerializable {

    private $username;
    private $password;

    /**
     * Create a new HttpAuth.
     *
     * @param string $username Username to use.
     * @param string $password Password to use.
     */
    public function __construct($username = null, $password = null) {
        $this->username = $username;
        $this->password = $password;
    }

    public function jsonSerialize(): array
    {
        $retval = array(
            "Username" => $this->username,
            "Password" => $this->password
        );

        return $retval;
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
