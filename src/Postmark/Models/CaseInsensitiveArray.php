<?php
namespace Postmark\Models;

/**
 * CaseInsensitiveArray allows accessing elements with mixed-case keys.
 *
 * This allows access to the array to be very forgiving. (i.e. If you access something
 * with the wrong CaSe, it'll still find the correct element)
 */
class CaseInsensitiveArray implements \ArrayAccess, \Iterator {
	private $_container = array();
	private $_pointer = 0;

	private function fixOffsetName($offset) {
		return preg_replace('/_/', '', strtolower($offset));
	}

	/**
	 * Initialize a CaseInsensitiveArray from an existing array.
	 *
	 * @param array $initialArray The base array from which to create the new array.
	 */
	public function __construct(Array $initialArray = array()) {
		$this->_container = array_change_key_case($initialArray);
	}

	public function offsetSet(mixed $offset, mixed $value): void {
		if (is_string($offset)) {
			$offset = $this->fixOffsetName($offset);
		}

		if (is_null($offset)) {
			$this->_container[] = $value;
		} else {
			$this->_container[$offset] = $value;
		}
	}

	public function offsetExists(mixed $offset): bool {
		if (is_string($offset)) {
			$offset = $this->fixOffsetName($offset);
		}

		return isset($this->_container[$offset]);
	}

	public function offsetUnset(mixed $offset): void {
		if (is_string($offset)) {
			$offset = $this->fixOffsetName($offset);
		}

		unset($this->_container[$offset]);
	}

	public function offsetGet(mixed $offset): mixed {
		if (is_string($offset)) {
			$offset = $this->fixOffsetName($offset);
		}
		return isset($this->_container[$offset]) ?
		$this->_container[$offset] : null;
	}

	public function current(): mixed {
		// use "offsetGet" instead of indexes
		// so that subclasses can override behavior if needed.
		return $this->offsetGet($this->key());
	}

	public function key(): mixed {
        $keys = array_keys($this->_container);
		return $keys[$this->_pointer];
	}

	public function next(): void {
		$this->_pointer++;
	}

	public function rewind(): void {
		$this->_pointer = 0;
	}

	public function valid(): bool {
		return count(array_keys($this->_container)) > $this->_pointer;
	}
}

#EOF#
