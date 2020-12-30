<?php 

	namespace Back\Users;

	class Modules {

		private $objects = [];
		private $index = 0;

		public function add($name, Array $data = []) {

			$key = mb_strtolower($name, 'UTF-8');

			if (isset($this->objects[$key])) {

				$this->objects[$key]->setSettings($data);
				return false;

			}

			$object = new Module($name, $data);
			$this->objects[$key] = $object;

			foreach ($object->gteDependencies() as $name)
				$this->add($name);

		}

		public function addModule(Module $module) {

			$key = mb_strtolower($module->getName(), 'UTF-8');
			if (isset($this->objects[$key])) return false;

			$this->objects[$key] = $module;

			foreach ($module->gteDependencies() as $name)
				$this->add($name);

		}

		public function &getAll() { return $this->objects; }

		public function getByName($name) {

			$key = mb_strtolower($name, 'UTF-8');

			if (isset($this->objects[$key]))
				return $this->objects[$key];

			else return false;

		}

		public function exists($name) {

			$key = mb_strtolower($name, 'UTF-8');
			return isset($this->objects[$key]);

		}

		public function getCurrent() {

			reset($this->objects);
			return current($this->objects);

		}

		public function isEmpty() { return empty($this->objects); }

	}
