<?php

namespace Websm\Framework\Cart;

interface CartPoolInterface {

	/**
	 * createItem Создаст новый товар в корзине.
	 * 
	 * @param string $id 
	 * @access public
	 * @return CartItemInterface
	 */
	public function createItem($id);

	/**
	 * add Добавляет объект товара в корзину.
	 * 
	 * @param CartItemInterface $item 
	 * @access public
	 * @return CartPoolInterface
	 */
	public function add(CartItemInterface $item);

	/**
	 * remove Удаляет товар из корзины.
	 * 
	 * @param string $id 
	 * @access public
	 * @return CartPoolInterface
	 */
	public function remove($id);

	/**
	 * getItem Вернет объект товара из корзины
	 * 
	 * @param string $id 
	 * @access public
	 * @return CartItemInterface
	 */
	public function getItem($id);

	/**
	 * getCount Вернет кольчество товаров в корзине.
	 * 
	 * @access public
	 * @return int
	 */
	public function getCount();

	/**
	 * getSumm Вернет общую стоимость товаров в корзине.
	 * 
	 * @access public
	 * @return float
	 */
	public function getSumm();

	/**
	 * getItems Вернет массив товаров в корзине.
	 * 
	 * @access public
	 * @return Array
	 */
	public function getItems();

	/**
	 * clear Очищает корзину.
	 * 
	 * @access public
	 * @return void
	 */
	public function clear();

	/**
	 * isEmpty Проверит корзину на пустоту.
	 * 
	 * @access public
	 * @return boolean
	 */
	public function isEmpty();

	/**
	 * has Проверит существование товара в корзине
	 * 
	 * @param string $id 
	 * @access public
	 * @return boolean
	 */
	public function has($id);

}
