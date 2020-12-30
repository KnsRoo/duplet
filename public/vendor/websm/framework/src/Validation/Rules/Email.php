<?php

	namespace Websm\Framework\Validation\Rules;

	class Email extends AbstractRule {

		public function check($field, &$data = []) { return true; }

		public static function inlineCheck($data) {

			return filter_var($data, FILTER_VALIDATE_EMAIL)
				? true
				: false;

		}

	}
