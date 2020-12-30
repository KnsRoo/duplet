<?php

	namespace Core\Misc;

	use \Core\Misc\Cyr2Lat;
	use \Core\Db\ActiveRecord;

	class NewChpu{

		protected static $_aliases = [
			'id' => 'id',
			'cid' => 'cid',
			'chpu' => 'chpu',
			'title' => 'title',
		];

		private final function __construct() {}

		/**
		 * @brief Создает ссылку с нормализацией слеша.
		 * @param $prefix Префикс ссылки.
		 * @param $ending окончание ссылки.
		 * @return String
		 *
		 * Пример:
		 *
		 * @code
		 * 	echo Chpu::concat('prefix', 'ending');
		 * // prefix/ending
		 * @endcode
		 */

		public static function concat($prefix = '', $ending = '') {

			$ending = preg_replace('/\s+/u', '-', $ending);
			$ending = preg_replace('/[^\w\-]/u', '', $ending);
			return preg_replace('/\/+/u', '/', $prefix.'/'.$ending);

		}

		/**
		 * @brief Создает ссылку с нормализацией слеша и транслитерацией.
		 * @see concat
		 */

		public static function build($prefix = '', $ending = '') {

			$chpu = Cyr2Lat::conv(self::concat($prefix, $ending));
			return preg_replace('/^\//', '', $chpu);

		}

		/**
		 * @brief Запоняет поле ответственное за чпу в объекте
		 * ActiveRecord на основе уже заполненных данных.
		 * @param $ar Объект ActiveRecord.
		 * @param $aliases Массив псевдонимов важных полей.
		 * @return Void
		 *
		 * Примеры:
		 *
		 * @code
		 *
		 * 	$ar = new ActiveRecord;
		 * 	$ar->title = 'Тест';
		 * 	$ar->cid = '123'; // раздел с $title = 'Корень';
		 * 	
		 * 	Chpu::inject($ar);
		 *
		 * 	echo $ar->chpu; // выведет koren/test
		 *
		 * @endcode
		 *
		 * Пример с не стандартными полями:
		 * @code
		 *
		 * 	$ar = new ActiveRecord;
		 * 	$ar->index = md5(uniqid());
		 * 	$ar->name = 'Тест';
		 * 	$ar->cat = '123'; // раздел с $title = 'Корень';
		 * 	
		 * 	Chpu::inject(
		 * 		$ar,
		 * 		[
		 * 			'id' => 'index',
		 * 			'cid' => 'cat',
		 * 			'title' => 'name',
		 * 			'chpu' => 'url',
		 * 		]
		 * 	);
		 *
		 * 	echo $ar->url; // выведет /koren/test
		 *
		 * @endcode
		 */

		public static function inject($ar, Array $aliases = []) {

			if (!($ar instanceof ActiveRecord))
				throw new \Exception('Object not extended ActiveRecord .');

			$aliases = array_merge(self::$_aliases, $aliases);
			unset($aliases['ar']);

			extract($aliases);

			$category = $ar->$cid;

			if ($category instanceof ActiveRecord)
				$prefix = $category->$chpu;

			else $prefix = $ar::query()
				->select([$chpu])
				->where([$id => $category])
				->get($chpu);

			$ar->$chpu = self::build($prefix, $ar->$title);

		}


	}
