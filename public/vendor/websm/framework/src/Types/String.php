<?php

namespace Websm\Framework\Types;

use Websm\Framework\Exceptions\BaseException as Exception;

class String {

	private $data = '';

	public static function wrap($string) {

		return new self($string);

	}

	public function __construct($string) {

		if (!is_string($string))
			throw new Exception('Type not string.');

		$this->data = $string;

	}

	public function __toString() {

		return $this->unwrap();

	}

	public function unwrap() {

		return $this->data;

	}

}
