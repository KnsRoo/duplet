<?php

namespace Websm\Framework\NoSQL;

use Exception;

class SessionStorage implements NoSQLInterface {

	public function __construct() {

		if (!isset($_SESSION)) session_start();

	}

	public function set($key, $value) {

		$_SESSION[$key] = $value;

	}

	public function get($key) {

		if (!$this->exists($key))
			throw new Exception('Not found');

		return $_SESSION[$key];

	}

	public function delete($keys) {

		if (!is_array($keys)) $keys = [$keys];

		foreach ($keys as $key)
			unset($_SESSION[$key]);

		return true;

	}

	public function setTimeout($key, $sec) {

		throw new Exception('Not used this method.');

	}

	public function getTimeout($key) {

		throw new Exception('Not used this method.');

	}

	public function exists($key) {

		return isset($_SESSION[$key]);

	}

	public function keys($pattern = '.*') {

		return array_map(function ($key) {

			if (preg_match('/'.$pattern.'/ui', $key))
				return $key;

		}, array_keys($_SESSION));

	}

}
