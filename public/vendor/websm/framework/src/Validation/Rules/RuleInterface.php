<?php

	namespace Websm\Framework\Validation\Rules;

	interface RuleInterface {

		public function setMessage($message);

		public function getMessage();

		public function check($field, &$data = []);

	}
