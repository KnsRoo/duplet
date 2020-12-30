<?php

	namespace Core;

	class Temp{

		public static $notify = []; /**< Массив уведомлений.*/
		public static $anyJs = ''; /**< JavaScript для вставки в корневой шаблон.*/ 
		public static $anyCss = ''; /**< CSS для вставки в корневой шаблон.*/

		/**
		 * @brief Подключает JS файл.
		 * @param $file Путь к файлу.
		 * @return Void
		 *
		 * Пример использования:
		 *
		 * @code
		 * 	\Core\Temp::requireJs(_JS.'/example.js');
		 * @endcode
		 */

		final public static function requireJs($file = ''){
			$file = _JS.'/'.$file;
			self::$anyJs .= file_exists($file) && is_file($file) ? file_get_contents($file) : '';
		}

		/**
		 * @brief Добавляет JS в виде строки;
		 * @param $data Строка представляющая JS код.
		 *
		 * Пример использования:
		 *
		 * @code
		 * 	\Core\Temp::insJs('alert("Привет!");');
		 * @endcode
		 */

		final public static function insJs($data = ''){
			self::$anyJs .= isset($data[0]) ? $data : '';
		}

		/**
		 * @brief Подключает CSS файл.
		 * @param $file Путь к файлу.
		 * @return Void
		 *
		 * Пример использования:
		 *
		 * @code
		 * 	\Core\Temp::requireJs(_CSS.'/example.css');
		 * @endcode
		 */

		final public static function requireCss($file = ''){
			$file = _CSS.'/'.$file;
			self::$anyCss .= file_exists($file) && is_file($file) ? file_get_contents($file) : '';
		}

		/**
		 * @brief Добавляет CSS в виде строки;
		 * @param $data Строка представляющая CSS код.
		 *
		 * Пример использования:
		 *
		 * @code
		 * 	\Core\Temp::insCss('.example{color: #fff;}');
		 * @endcode
		 */

		final public static function insCss($data = ''){
			self::$anyCss .= isset($data[0]) ? $data : '';
		}

		/**
		 * @brief Генерирует шаблон.
		 * @param $filename Путь к файлу шаблона.
		 * @param $data Массив параметров передаваемых в шаблон.
		 *
		 * Пример использования:
		 *
		 * example.php:
		 * @code
		 * 	$this->render(_TEMP.'/example.tpl', ['hello'=>'Привет', 'world'=>'Мир']);
		 * @endcode
		 *
		 * example.tpl:
		 * @code
		 * 	<div><?= $hello;?><br/><?= $world;?></div>
		 * @endcode
		 */

		final public function render($filename = '', Array $data = []){

			if(is_file($filename)){
				$data && extract($data, EXTR_REFS);
				ob_start();
				include $filename;
				return ob_get_clean();
			}
			return false;

		}

	}
