<?php

declare(strict_types=1);

namespace Postmark\Models;

use ArrayAccess;
use Iterator;

use function array_change_key_case;
use function array_keys;
use function count;
use function is_string;
use function str_replace;
use function strtolower;

use const CASE_LOWER;

/**
 * CaseInsensitiveArray allows accessing elements with mixed-case keys.
 * This allows access to the array to be very forgiving. (i.e. If you access something
 * with the wrong CaSe, it'll still find the correct element)
 *
 * @internal Postmark
 */
class CaseInsensitiveArray implements ArrayAccess, Iterator
{
    /** @var array<array-key, mixed> */
    private array $data;
    private int $pointer = 0;

    private function normaliseOffset(string $offset): string
    {
        return str_replace('_', '', strtolower($offset));
    }

    /**
     * Initialize a CaseInsensitiveArray from an existing array.
     *
     * @param array<array-key, mixed> $initialArray The base array from which to create the new array.
     */
    public function __construct(array $initialArray = [])
    {
        $this->data = array_change_key_case($initialArray, CASE_LOWER);
    }

    /** @param array-key|null $offset */
    public function offsetSet($offset, mixed $value): void
    {
        if (is_string($offset)) {
            $offset = $this->normaliseOffset($offset);
        }

        if ($offset === null) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    /** @param array-key $offset */
    public function offsetExists($offset): bool
    {
        if (is_string($offset)) {
            $offset = $this->normaliseOffset($offset);
        }

        return isset($this->data[$offset]);
    }

    /** @param array-key $offset */
    public function offsetUnset($offset): void
    {
        if (is_string($offset)) {
            $offset = $this->normaliseOffset($offset);
        }

        unset($this->data[$offset]);
    }

    /** @param array-key $offset */
    public function offsetGet($offset): mixed
    {
        if (is_string($offset)) {
            $offset = $this->normaliseOffset($offset);
        }

        return $this->data[$offset] ?? null;
    }

    public function current(): mixed
    {
        // use "offsetGet" instead of indexes
        // so that subclasses can override behavior if needed.
        return $this->offsetGet($this->key());
    }

    /** @return array-key */
    public function key(): mixed
    {
        return array_keys($this->data)[$this->pointer];
    }

    public function next(): void
    {
        $this->pointer++;
    }

    public function rewind(): void
    {
        $this->pointer = 0;
    }

    public function valid(): bool
    {
        return count(array_keys($this->data)) > $this->pointer;
    }
}
