<?php

	/** Интерфейс модуля для административной части сайта.*/

	namespace Core;

	interface ModuleInterface{

		/**
		 * @brief Функция инициализации модуля.
		 * Может содержать обработчики запросов.
		 * @return Void
		 *
		 * @code
		 * 	public function init() {}
		 * @endcode
		 */

		/* public function init(); */


		public function getContent();

		/**
		 * setSettings 
		 *
		 * Устанавливает настроки для модуля.
		 * 
		 * @param Array $props 
		 * @access public
		 * @return void
		 */
		public function setSettings(Array &$props = []);

		/**
		 * getSettings 
		 *
		 * Вернёт массив настроек модуля.
		 * 
		 * @access public
		 * @return Array
		 */
		public function getSettings();

		/**
		 * @brief Возвращает HTML вёрстку настроек модуля.
		 * @return Bool;
		 *
		 * @code
		 * 	public function getSettings($name = '', Array $permitions){}
		 * @endcode
		 */

		public function getSettingsContent($name = '', Array $permitions);

	}
