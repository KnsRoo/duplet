<?php

	namespace Websm\Framework\Validation\Rules;

	class CallabckWrapper extends AbstractRule {

		protected $handler;

		private function __construct() {}

		public static function handler(callable $call) {

			$instance = new self;
			$instance->handler = $call;
			return $instance;

		}

		public function check($key, &$data = []) {

			return call_user_func_array($this->handler, [$key, &$data]);

		}

	}
