<?php

namespace \Postmark\Model;

// This class allows for case-insensitive member lookup based
// on this StackOverflow answer: http://stackoverflow.com/a/4240055/32238

class CaseInsensitiveArray implements ArrayAccess {
	private $_container = array();

	public function __construct(Array $initial_array = array()) {
		$this->_container = array_map("strtolower", $initial_array);
	}

	public function offsetSet($offset, $value) {
		if (is_string($offset)) {
			$offset = strtolower($offset);
		}

		if (is_null($offset)) {
			$this->container[] = $value;
		} else {
			$this->container[$offset] = $value;
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

		unset($this->container[$offset]);
	}

	public function offsetGet($offset) {
		if (is_string($offset)) {
			$offset = strtolower($offset);
		}

		return isset($this->container[$offset])
		? $this->container[$offset]
		: null;
	}
}

?>