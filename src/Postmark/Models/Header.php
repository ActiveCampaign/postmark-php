<?php

declare(strict_types=1);

namespace Postmark\Models;

use JsonSerializable;

use function assert;

final class Header implements JsonSerializable
{
    /**
     * @param non-empty-string $name
     * @param scalar|null      $value
     */
    public function __construct(private string $name, private $value) // phpcs:ignore
    {
    }

    /** @return array{Name: non-empty-string, Value: scalar|null} */
    public function jsonSerialize(): array
    {
        return [
            'Name' => $this->name,
            'Value' => $this->value,
        ];
    }

    /**
     * @param array<string, scalar|null> $values
     *
     * @return list<self>|null
     */
    public static function listFromArray(array|null $values): array|null
    {
        if ($values === [] || $values === null) {
            return null;
        }

        $list = [];
        foreach ($values as $name => $value) {
            assert(! empty($name));
            $list[] = new self($name, $value);
        }

        return $list;
    }
}
