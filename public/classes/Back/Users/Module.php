<?php

	namespace Back\Users;

	class Module {

		private $object = null;
		private $permitions = [];
		private $dependencies = [];
		private $hidden = false;
		private $disabled = false;
		private $system = false;
		private $setting = false;
		private $class = '';
		private $dir = '';
		private $title = '';
		private $icon = 'icons/empty.svg';
		private $name = '';

		private static $defaultMeta = [
			'title' => 'empty',
			'icon' => 'icons/empty.svg',
			'system' => false,
			'hidden' => false,
			'disabled' => false,
			'setting' => false,
			'dependencies' => [],
		];

		public function __construct($name, Array $data) {

			$this->name = $name;
			$this->class = '\\Back\\'.$name.'\\'.$name;
			$this->dir = _BACK.'/'.$name;

			$meta = [];
			if (file_exists($this->dir.'/meta.json')) {

				$meta = json_decode(file_get_contents($this->dir.'/meta.json'), true);
				$meta = array_merge(self::$defaultMeta, $meta);

			}
			else throw new \Exception('meta.json not found.');

			$this->title = $meta['title'];
			$this->icon = $meta['icon'];
			$this->hidden = $meta['hidden'];
			$this->disabled = $meta['disabled'];
			$this->system = $meta['system'];
			$this->setting = $meta['setting'];
			$this->dependencies = $meta['dependencies'];

			$class = $this->class;
			$this->object = new $class;

			$this->setSettings($data);

		}

		public function getSettingsContent() {

			return $this->object->getSettingsContent('', $this->permitions);

		}

		public function setSettings(Array $data) {

			if (isset($data['permitions']) && is_array($data['permitions'])) 
				$this->permitions = $data['permitions'];

			$this->object->setSettings($this->permitions);

		}

		public function getSettings() {

			return $this->object->getSettings();

		}

		public function getIcon() { return $this->icon; }

		public function gteDependencies() { return $this->dependencies; }

		public function getObject() { return $this->object; }

		public function getTitle() { return $this->title; }

		public function getName() { return $this->name; }

		public function isHidden() { return $this->hidden; }

		public function isDisabled() { return $this->disabled; }

		public function isSystem() { return $this->system; }

		public function isSetting() { return $this->setting; }

	}
