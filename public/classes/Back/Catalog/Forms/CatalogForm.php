<?php

	namespace Back\Catalog\Forms;

	use \Core\Misc as Misc;

	class CatalogForm extends Misc\Form {

		public $title = '';
		public $count = 1;
		public $price = 0;
		public $preview = '';
		public $about = '';
		public $sort = 0;

		public function getRules(){
			return [
				['title', 'striplace', 'on' => 'createCat'],
				['title', 'required', 'on' => 'createCat'],
				['title', 'striplace', 'on' => 'updateCat'],
				['title', 'required', 'on' => 'updateCat'],

				['title', 'length', 'on' => 'createCat', 'min' => 2,
				 'message' => 'Для созания категории, название должно быть более 2х символов'],
				['title', 'length', 'on' => 'updateCat', 'min' => 2,
				 'message' => 'Для изменения категории, название должно быть более 2х символов'],

				['title', 'length', 'on' => 'createProduct', 'min' => 2,
				 'message' => 'Для созания товара, название должно быть более 2х символов'],
				['title', 'length', 'on' => 'updateProduct', 'min' => 2,
				 'message' => 'Для изменения товара, название должно быть более 2х символов'],

				[['price', 'count'], 'native'],
				['count', 'int'],
				['price', 'numeric'],
				['preview', 'striplace'],
				['about', 'pass'],

				['sort', 'int', 'on' => 'sort' , 'message' => 'Значение должно быть целым положительным числом'],
				['sort', 'limit', 'on' => 'sort', 'min' => 0, 'message' => 'Значение должно быть целым положительным числом']
			];
		}

	}
