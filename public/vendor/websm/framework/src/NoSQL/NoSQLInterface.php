<?php

	namespace Websm\Framework\NoSQL;

	interface NoSQLInterface {

		/**
		 * set 
		 *
		 * Создает запись в бд с ключем $key и значением $value
		 *
		 * @param string $key 
		 * @param string $value 
		 * @access public
		 * @return boolean
		 */
		public function set($key, $value);

		/**
		 * delete 
		 *
		 * Удаляет запись из бд с указанным ключем.
		 *
		 * @param mixed $keys строка или массив ключей.
		 * @access public
		 * @return boolean
		 */
		public function delete($keys);

		/**
		 * get 
		 *
		 * Вернет значение из бд по указанному ключу.
		 *
		 * @param string $key 
		 * @access public
		 * @return string or boolean
		 */
		public function get($key);

		public function setTimeout($key, $sec); 

		public function getTimeout($key); 

		/**
		 * exists 
		 *
		 * Проверит существоания записи с указанным ключем.
		 *
		 * @param string $key 
		 * @access public
		 * @return boolean
		 */
		public function exists($key);

		/**
		 * keys 
		 *
		 * 	Должен вернуть массив ключей подходящих под $pattern
		 * 
		 * @param string $pattern 
		 * @access public
		 * @return array
		 */
		public function keys($pattern = '*');

	}
