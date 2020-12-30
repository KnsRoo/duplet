<?php

	namespace Back\Layout;

	use Core\Users;

	class LayoutModel{

		private static $link;

		private static $modules = [];

		private static $defaults = [
			'title' => 'empty',
			'icon' => 'icons/empty.svg',
			'system' => false,
			'hidden' => false,
			'disabled' => false,
			'permitions' => [],
			'dependencies' => [],
		];

		public static function init() {

			/* self::$link = $_link; */

			foreach (Users::get()->modules as $name => &$props) {

				self::getMeta($name, $props);

			}

			foreach (self::$modules as $name => $meta) {

				self::satisfyDep($meta['dependencies']);

			}

		}

		protected static function getMeta($name, $props = []) {

			$key = mb_strtolower($name, 'UTF-8');
			$meta = [];
			if(isset(self::$modules[$key])) return;
			self::$modules[$key] = &$meta;
			$dir = _BACK.'/'.$name;
			file_exists($dir.'/meta.json') &&
				$meta = json_decode(file_get_contents($dir.'/meta.json'), true);
			$meta = array_merge(self::$defaults, $meta);
			$meta = array_merge($meta, $props);

			$meta['name'] = $name;
			$meta['file'] = _BACK.'/'.$name.'/'.$name.'.php';
			$meta['module'] = '\Back\\'.$name.'\\'.$name;
			$meta['props'] = $props;
			$meta['dependencies'] = &$meta['dependencies'];
			return $meta;

		}

		protected static function satisfyDep(Array $dep) {

			foreach($dep as $name) {

				$meta = self::getMeta($name);
				is_array($meta['dependencies']) && self::satisfyDep($meta['dependencies']);

			}

		}

		public static function &getModuleByName($name = '') {

			$name = mb_strtolower($name, 'UTF-8');
			if (isset(self::$modules[$name]) 
				&& self::$modules[$name]['disabled'] !== true) {

				return self::$modules[$name];

			}

			$empty = [];
			return $empty;

		}

		public static function getModules() {

			return self::$modules;

		}

		public static function &getCurrent() {

			reset(self::$modules);
			$key = key(self::$modules);
			return self::$modules[$key];

		}

		public static function initModule(Array &$data) {

			extract($data, EXTR_REFS);

			if(is_object($module)) return $module;

			if(file_exists($file) && is_subclass_of($module, '\Core\Module')) {
				$module = new $module($permitions);
				return $module;
			}

		}

		public static function getModuleName($moduleObj = []) {

			foreach (self::$modules as $key => $value) {
				if ($moduleObj == $value['module']) return $key;
			}

		}

	}
