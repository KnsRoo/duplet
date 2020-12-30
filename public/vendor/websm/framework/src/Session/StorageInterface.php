<?php

namespace Websm\Framework\Session;

interface StorageInterface {

	/**
	 * set Устанавливает значение по ключу.
	 * 
	 * @param string $key 
	 * @param mixed $value 
	 * @access public
	 * @return void
	 */
	public function set($key, $value);

	/**
	 * get Получает значение по ключу.
	 * 
	 * @param string $key 
	 * @access public
	 * @return void
	 */
	public function get($key);

	/**
	 * has Проверяет существует ли ключ.
	 * 
	 * @param mixed $key 
	 * @access public
	 * @return void
	 */
	public function has($key);

	/**
	 * delete Удаляет запись по ключу;
	 * 
	 * @param mixed $key 
	 * @access public
	 * @return void
	 */
	public function delete($key);

}
