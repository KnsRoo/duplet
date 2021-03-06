<?php

	namespace Websm\Framework\Di;

	class RawArgument {

		private $data;

		public function __construct($data = null) {
			$this->data = $data;
		}

		public function getData() {
			return $this->data;
		}

		public function setData($data = null) {
			$this->data = $data;
		}

	}
