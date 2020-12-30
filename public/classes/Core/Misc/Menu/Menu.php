<?php

	namespace Core\Misc\Menu;

	use \Core\Db\ActiveRecord;
	use \Core\Response;

	class Menu extends Response{

		protected $_templates = [
			'layout' => __DIR__.'/temp/layout.tpl',
			'item'   => __DIR__.'/temp/item.tpl',
		];

		protected $_qb;
		protected $_handler;
		protected $_deep = null;
		protected $_hidden = true; 

		public function __toString() {

			return $this->get();

		}

		/**
		 *@brief Иницилизирует меню
		 *@param $qb* Объект Qb с выборкой
		 *@param $deep Глубина вложеностей
		 *@return Object
		 *@code
		 *$menu = \Core\Misc\Menu\Menu::init($qb, $deep);
		 *@endcode
		 **/

		public static function init($qb, $deep = null, $hidden = true) {

			$object          = new self;
			$object->_qb     = $qb->order('sort')->getAll();
			$object->_deep   = $deep;
			$object->_hidden = $hidden;

			return $object;

		}

		protected function getTemplates() {

			return $this->_templates;

		}

		public function setTemplates(Array $templates) {
			$this->_templates = $templates;
		}

		protected function genMenu(Array $activeAr, $deep = null, $hidden = true) {

			$ret   = '';
			$items = '';

			extract($this->getTemplates());

			foreach($activeAr as $object) {
				if($object->visible == $hidden) {

					if(is_null($deep) || $deep > 0) {
						$items = $this->genMenu($object::find(['cid' => $object->id])->order('sort')->getAll(), is_null($deep) ? null : $deep - 1);
					}

					$ret .= $this->render($item, ['item' => $object, 'items' => $items]);
				}
			}
			return $ret ? $this->render($layout, ['items' => $ret]) : '';

		}

		/**
		 *@return String
		 *@code
		 *$menu = \Core\Misc\Menu\Menu::init($qb, $deep);
		 *$menu->get();
		 *@endcode
		 **/

		public function get(){

			return $this->genMenu($this->_qb, $this->_deep, $this->_hidden);

		}


	}

?>
