<?php

	namespace Back\Pages\Forms;

	use \Core\Misc as Misc;

	class PageForm extends Misc\Form {

		public $title = '';
		public $text = '';
		public $sort = 0;

		public function getRules() {
			return [
				['title', 'striplace'],
				['title', 'length', 'on' => 'create', 'min' => 2,
				 'message' => 'Для созания раздела, название должно быть более 2х символов'],
				['title', 'length', 'on' => 'update', 'min' => 2,
				 'message' => 'Для изменения раздела, название должно быть более 2х символов'],
				['text', 'pass', 'on' => 'update'],
				['sort', 'int', 'on' => 'sort' , 'message' => 'Значение должно быть целым положительным числом'],
				['sort', 'limit', 'on' => 'sort', 'min' => 0, 'message' => 'Значение должно быть целым положительным числом']
			];
		}

	}
