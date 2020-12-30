<?php

	namespace Websm\Framework\Validation\Rules;

	use Websm\Framework\Validation\Exceptions;

	class Native extends AbstractRule {

		private $context;

		public function __construct($context) {

			if(!is_object($context))
				throw new Exceptions\TypeException('$context is not object.');

			$this->context = $context;

		}

		public function check($field, &$data = []) {

			if(!empty($data[$field])) return true;

			$class = get_class($this->context);
			$vars = get_class_vars($class);
			$data[$field] = $vars[$field];

		}

	}
