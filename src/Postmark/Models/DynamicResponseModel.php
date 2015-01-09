<?php

namespace Postmark\Models;

/**
 * The DynamicResponseModel allows flexible and forgiving access to responses from the Postmark API.
 *
 * Most responses from the PostmarkClient return a DynamicResponseModel, so understanding how it works is useful.
 *
 * Essentially, you can use either object or array index notation to lookup values. The member names are case insensitive,
 * so that each of these are acceptable ways of accessing "id" on a server response, for example.
 *
 * .. code-block:: php
 *
 *      //Create a client instance and get server info.
 *      $client = new PostmarkClient($server_token);
 *      $server = $client->getServer();
 *
 *      //You have many ways of accessing the same members:
 *      $server->id;
 *      $server->Id;
 *      $server["id"];
 *      $server["ID"];
 */

class DynamicResponseModel extends CaseInsensitiveArray {

	/**
	 * Create a new DynamicResponseModel from an associative array.
	 *
	 * :param Array $data: The source data.
	 */
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