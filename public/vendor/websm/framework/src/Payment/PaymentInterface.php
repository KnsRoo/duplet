<?php

namespace Websm\Framework\Payment;

interface PaymentInterface{

	/**
	 * setAmount 
	 *
	 * Установить стоимость
	 * 
	 * @param mixed $amount 
	 * @access public
	 * @return void
	 */
	public function setAmount($amount);

	/**
	 * setSuccessUrl
	 *
	 * Устанавливает url успешной операции.
	 * 
	 * @param  $amount
	 * @access public
	 * @return void
	 */
	public function setSuccessUrl($url);

	/**
	 * setFailUrl
	 *
	 * Устанавливает url не успешной операции.
	 * 
	 * @param  $amount
	 * @access public
	 * @return void
	 */
	public function setFailUrl($url);

	/**
	 * getPaymentUrl
	 *
	 * Производит вернет url операции оплаты.
	 * 
	 * @access public
	 * 
	 */
	public function getPaymentUrl();

	/**
	 * orderStatus
	 *
	 * Производит операцию проверки статуса заказа.
	 * 
	 * @access public
	 * @return boolean
	 * 
	 */
	public function getOrderStatus();

	/**
	 * getOrderId 
	 *
	 * Получает идентификатор заказа.
	 * 
	 * @access public
	 * @return void
	 */
	public function getOrderId();

}
