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

	/**
	 * Initialize a CaseInsensitiveArray from an existing array.
	 *
	 * @param array $initial_array
	 */
	public function __construct(Array $initial_array = array()) {
		$this->_container = array_change_key_case($initial_array);
	}

	/**
	 * Assign a value at the specified offset.
	 *
	 * @param mixed $offset The key of of the value being set.
	 * @param mixed $value The value being set.
	 */
	public function offsetSet($offset, $value) {
		if (is_string($offset)) {
			$offset = strtolower($offset);
		}

		if (is_null($offset)) {
			$this->_container[] = $value;
		} else {
			$this->_container[$offset] = $value;
		}
	}

	public function offsetExists($offset) {
		if (is_string($offset)) {
			$offset = strtolower($offset);
		}

		return isset($this->_container[$offset]);
	}

	public function offsetUnset($offset) {
		if (is_string($offset)) {
			$offset = strtolower($offset);
		}

		unset($this->_container[$offset]);
	}

	public function offsetGet($offset) {
		if (is_string($offset)) {
			$offset = strtolower($offset);
		}
		return isset($this->_container[$offset]) ?
		$this->_container[$offset] : null;
	}

	public function current() {
		// use "offsetGet" instead of indexes
		// so that subclasses can override behavior if needed.
		return $this->offsetGet($this->key());
	}

	public function key() {
		return array_keys($this->_container)[$this->_pointer];
	}

	public function next() {
		$this->_pointer++;
	}

	public function rewind() {
		$this->_pointer = 0;
	}

	public function valid() {
		return count(array_keys($this->_container)) > $this->_pointer;
	}
}

?>