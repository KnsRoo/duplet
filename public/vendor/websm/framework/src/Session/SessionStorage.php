<?php

namespace Websm\Framework\Session;

use Websm\Framework\Session\Exceptions\BaseException;
use Websm\Framework\Session\Exceptions\InvalidArgumentException;
use Websm\Framework\Session\Exceptions\NotFoundException;

class SessionStorage implements StorageInterface {

	public function __construct() {

		if (!isset($_SESSION)) session_start();

	}

	public function get($key) {

		if (!is_string($key))
			throw new InvalidArgumentException('key not string.');

		if (!$this->has($key))
			throw new NotFoundException('not found value');

		return $_SESSION[$key];

	}

	public function set($key, $value) {

		if (!is_string($key))
			throw new InvalidArgumentException('key not string.');

		$_SESSION[$key] = $value;

	}

	public function has($key) {

		if (!is_string($key))
			throw new InvalidArgumentException('key not string.');

		if (isset($_SESSION[$key]) && $_SESSION[$key] !== null) return true;
		else return false;

	}

	public function delete($key) {

		if (!is_string($key))
			throw new InvalidArgumentException('key not string.');

		unset($_SESSION[$key]);

	}

}
