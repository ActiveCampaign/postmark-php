<?php

namespace Postmark\Models;

use ArrayAccess;
use Iterator;
use ReturnTypeWillChange;

 // phpcs:ignore

/**
 * CaseInsensitiveArray allows accessing elements with mixed-case keys.
 *
 * This allows access to the array to be very forgiving. (i.e. If you access something
 * with the wrong CaSe, it'll still find the correct element)
 */
class CaseInsensitiveArray implements ArrayAccess, Iterator
{
    private $_container = [];
    private $_pointer = 0;

    /**
     * Initialize a CaseInsensitiveArray from an existing array.
     *
     * @param array $initialArray the base array from which to create the new array
     */
    public function __construct(array $initialArray = [])
    {
        $this->_container = array_change_key_case($initialArray);
    }

    #[ReturnTypeWillChange]
    public function offsetSet($offset, $value)
    {
        if (is_string($offset)) {
            $offset = $this->fixOffsetName($offset);
        }

        if (is_null($offset)) {
            $this->_container[] = $value;
        } else {
            $this->_container[$offset] = $value;
        }
    }

    #[ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        if (is_string($offset)) {
            $offset = $this->fixOffsetName($offset);
        }

        return isset($this->_container[$offset]);
    }

    #[ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        if (is_string($offset)) {
            $offset = $this->fixOffsetName($offset);
        }

        unset($this->_container[$offset]);
    }

    #[ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        if (is_string($offset)) {
            $offset = $this->fixOffsetName($offset);
        }

        return $this->_container[$offset] ?? null;
    }

    #[ReturnTypeWillChange]
    public function current()
    {
        // use "offsetGet" instead of indexes
        // so that subclasses can override behavior if needed.
        return $this->offsetGet($this->key());
    }

    #[ReturnTypeWillChange]
    public function key()
    {
        $keys = array_keys($this->_container);

        return $keys[$this->_pointer];
    }

    #[ReturnTypeWillChange]
    public function next()
    {
        ++$this->_pointer;
    }

    #[ReturnTypeWillChange]
    public function rewind()
    {
        $this->_pointer = 0;
    }

    #[ReturnTypeWillChange]
    public function valid()
    {
        return count(array_keys($this->_container)) > $this->_pointer;
    }

    private function fixOffsetName($offset)
    {
        return preg_replace('/_/', '', strtolower($offset));
    }
}
