<?php

	namespace Core;

	class User{

		public $login='';
		public $modules=[];

		public function __construct(Array $prop) {

			foreach($prop as $key => $val)
				$this->$key = ($key == 'login') ? $this->clear($val) : $val;

		}

		public function __get($name) { return false; }

		public function save($dir) {

			$file = var_export($this, true);
			$file = '<?php return class_exists(\''.__CLASS__.'\') ? '.$file.' : false; ?>';
			file_put_contents($dir.'/'.$this->login.'.php',$file);

		}

		public function set() {

			!isset($_SESSION) && session_start();
			$_SESSION['CoreUser'] = (Array)$this;

		}

		public function exists($dir) {

			$file = $dir.'/'.$this->login.'.php';

			return file_exists($file) ? $file : false; 

		}

		public static function __set_state(Array $prop) {

			return new self($prop);

		}

		private function clear($str) {

			$str = preg_replace('/[^\w\s\-\_\.]/u', '', $str);
			$str = trim($str);
			return $str;

		}

	}
?>
