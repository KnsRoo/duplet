<?php

namespace Websm\Framework\Payment;

use MInB_Client;
use MInB_Payment;

use Websm\Framework\Payment\Exceptions\PaymentException;

class MinBank implements PaymentInterface {	

	private $client = null;
	private $payment = null;

	private $orderID = null;
	private $sessionID = null;
	private $amount = 0;
	private $description = '';
	private $returnUrl = '';
	private $failUrl = '';
	private $debug = false;

	private $defaults = [
		'address' => 'https://mpit.minbank.ru:5443/Exec',
		'type' => 'TEST',
		'cert' => '',
		'key' => '',
		'certPasswd' => false,
		'caInfo' => false,
	];

	public function __construct($merchantID, Array $params = [], $debug = false) {

		$this->debug = $debug;

		$params = array_merge($this->defaults, $params);

		$infoParams = [];
		$infoParams['address'] = $params['address'];
		$infoParams['type'] = $params['type'];

		$certParams = [];
		$certParams['cert'] = $params['cert'];
		$certParams['key'] = $params['key'];
		$certParams['certPasswd'] = $params['certPasswd'];
		$certParams['caInfo'] = $params['caInfo'];

		$this->client = new MInB_Client($infoParams, $certParams);

		$this->payment = new MInB_Payment($merchantID);

	}

	public function setSuccessUrl($url) {

		$this->payment->setUrl('APPROVE', $url);
		return $this;

	}


	public function setFailUrl($url) {

		$this->payment
			->setUrl('DECLINE', $url)
			->setUrl('CANCEL', $url);

		return $this;

	}

	private function isUrlsSet() {

		$payment = $this->payment;

		if(
			$payment->getUrl('APPROVE') == false && 
			$payment->getUrl('DECLINE') == false &&
			$payment->getUrl('CANCEL') == false
		) {
			return false;
		}

		return true;

	}

	public function setAmount($amount) {

		if(!is_numeric($amount))
			throw new PaymentExeption('$amount is not numeric.');

		$amount = (int)round($amount * 100, 0);

		$this->payment->setAmount($amount);

		return $this;

	}

	private function isAmountSet() { 

		if($this->payment->getAmount() >= 0) 
			return true;

		return false;

	}

	public function getPaymentUrl() {

		if(!$this->isAmountSet()) 
			throw new PaymentException('Amount not set.');

		if(!$this->isUrlsSet()) 
			throw new PaymentException('URLs not set.');

		$this->payment
			->setOperation('CreateOrder')
			->setOrderType('Purchase')
			->setDescription('no description');

		$answer = $this->client->getAnswer($this->payment);

		if (!$answer) throw new PaymentException('Empty answer.');

		if ($answer['Status'] == '00') {

			$this->orderID   = $answer['OrderID'];
			$this->sessionID = $answer['SessionID'];

			$url = $answer['URL'];
			$url .= 'index.jsp?ORDERID='.$answer['OrderID'];
			$url .= '&SESSIONID='.$answer['SessionID'];

		} else {

			$message = 'Create url error. Status: '.$answer['Status'];
			throw new PaymentException($message);

		}

		return $url;

	}

	public function getOrderStatus() {

		// Настроим клиент

		$client = $this->client;
		$payment = $this->payment;

		$payment
			->setOperation('GetOrderInformation')
			->setOrderID($this->orderID)
			->setSessionID($this->sessionID);

		$answer = $client->getAnswer($payment);

		return $answer[0]['Orderstatus'] === 'APPROVED' ?: false;

	}

	public function getOrderId() {

		return $this->orderID;

	}

}
