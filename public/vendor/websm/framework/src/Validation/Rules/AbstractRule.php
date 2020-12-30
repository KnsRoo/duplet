<?php

	namespace Websm\Framework\Validation\Rules;

	use Websm\Framework\Validation\Exceptions;

	abstract class AbstractRule implements RuleInterface {

		protected $message;

		public static function __callStatic($name, $arguments) {

			$cellable = __CLASS__.'::'.$name;

			return $callable($arguments);

		}

		public function setMessage($message) {

			if(!is_string($message)) 
				throw new Exceptions\MessageException('Message is not string.');

			$this->message = $message;

		}

		public function getMessage() {

			return $this->message;

		}

		abstract public function check($field, &$data = []);

	}
