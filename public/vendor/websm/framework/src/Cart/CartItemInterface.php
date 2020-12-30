<?php

namespace Websm\Framework\Cart;

interface CartItemInterface {

	/**
	 * asArray возврат всех свойств товара в виде массива.
	 * 
	 * @access public
	 * @return Array
	 */
	public function asArray();

	/**
	 * bind Устанавливает свойства товара.
	 * 
	 * @param Array $data 
	 * @access public
	 * @return void
	 */
	public function setData(Array $data);

	/**
	 * setCount Установит количество товара в корзине.
	 * 
	 * @param mixed $count 
	 * @access public
	 * @return void
	 */
	public function setCount($count);

	/**
	 * getCount Вернет количество товара в корзине.
	 * 
	 * @access public
	 * @return int
	 */
	public function getCount();

	/**
	 * incCount Увеличит количество товара на 1.
	 * 
	 * @access public
	 * @return void
	 */
	public function incCount();

	/**
	 * decCount Уменьшит кольчество товара на 1.
	 * 
	 * @access public
	 * @return void
	 */
	public function decCount();

	/**
	 * setPrice Устанавливает стоимость одного товара.
	 * 
	 * @param float $price 
	 * @access public
	 * @return void
	 */
	public function setPrice($price);

	/**
	 * getPrice Вернет стоимость одного товара.
	 * 
	 * @access public
	 * @return float
	 */
	public function getPrice();

	/**
	 * getSumm Вернет стоимость товара с учетом его количества.
	 * 
	 * @access public
	 * @return float
	 */
	public function getSumm();

	/**
	 * getId Для доступа к идентификатору товара.
	 * 
	 * @access public
	 * @return mixed
	 */
	public function getId();

}
