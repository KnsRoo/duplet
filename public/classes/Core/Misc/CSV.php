<?php

	namespace Core\Misc;

	class CSV {

		private $rows = [];
		private $delimiter = ';';

		public static function parse($file, $delimiter = ';') {

			ini_set('auto_detect_line_endings', true);

			$csv = new self;
			$csv->delimiter = $delimiter;
			$handle = fopen($file, 'r');

			while ($row = fgetcsv($handle, 0, $delimiter))
				$csv->rows[] = $row;

			fclose($handle);
			return $csv;

		}

		public function __toString() { return $this->toString(); }

		public function setDelimiter($delimiter) {

			$this->delimiter = $delimiter;
			return $this;

		}

		public function addRow() { $this->rows[] = func_get_args(); }

		public function save($file) {

			$handle = fopen($file, 'w');

			if (!$handle) throw new \Exception('Opening $file error.');

			foreach($this->rows as $row)
				fputcsv($handle, $row, $this->delimiter);

			fclose($handle);

		}

		public function toString() {

			ob_start();
			$this->out();
			return ob_get_clean();

		}

		public function out() { $this->save('php://output'); }

	}
