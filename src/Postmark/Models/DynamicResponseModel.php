<?php

namespace Postmark\Models;

class DynamicResponseModel extends CaseInsensitiveArray {

	function __construct($data) {
		parent::__construct($data);
	}

	public function __get($name) {

		$value = $this[$name];

		// If there's a value and it's an array,
		// convert it to a dynamic response object, too.
		if ($value != NULL && is_array($value)) {
			$value = new DynamicResponseModel($value);
		}

		return $value;
	}

	// since we allow indexers, those should
	// return dynamic response models when appropriate.
	public function offsetGet($offset) {
		$result = parent::offsetGet($offset);
		if ($result != NULL && is_array($result)) {
			$result = new DynamicResponseModel($result);
		}
		return $result;
	}
}

?>