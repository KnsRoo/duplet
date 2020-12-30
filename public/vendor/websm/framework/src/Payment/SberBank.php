<?php

namespace Websm\Framework\Payment;

use Websm\Framework\Payment\Exceptions\PaymentException;

class SberBank implements PaymentInterface {

    const PAYMENT_URL = 'https://securepayments.sberbank.ru/payment/rest/register.do';
    const ORDER_STATUS_URL = 'https://securepayments.sberbank.ru/payment/rest/getOrderStatus.do';

    const FORMAT_GET_PAYMENT_URL = '?userName=%s&password=%s&orderNumber=%s&amount=%s&returnUrl=%s&failUrl=%s';
    const FORMAT_GET_ORDER_STATUS = '?orderId=%s&password=%s&userName=%s';

    protected $orderNumber = '';

    public $amount      = 0;
    public $description = '';
    public $failUrl     = '';
    public $returnUrl = '';

    private $userName = '';
    private $password = '';

    public function __construct($userName, $password) {

        $this->userName = $userName;
        $this->password = $password;
    }

    public function setAmount($amount) {

        if(!is_numeric($amount))
            throw new \Exception('amount is not numeric.');

        $this->amount = (int)round($amount * 100, 0);

        return $this;

    }

    public function setSuccessUrl($url) { 

        $this->returnUrl = $url;
        return $this;

    }

    public function setFailUrl($url) {

        $this->failUrl = $url;
        return $this;

    }

    public function getPaymentUrl() {

        $this->orderNumber = uniqid();

        $format = static::PAYMENT_URL;
        $format .= self::FORMAT_GET_PAYMENT_URL;

        $url = sprintf(
            $format,
            urlencode($this->userName),
            urlencode($this->password),
            urlencode($this->orderNumber),
            urlencode($this->amount),
            urlencode($this->returnUrl),
            urlencode($this->failUrl)
        );

        $result = file_get_contents($url);
        $result = json_decode($result);

        $this->orderId = $result->orderId;

        return $result->formUrl;

    }

    public function getOrderStatus() {

        $format = static::ORDER_STATUS_URL;
        $format .= self::FORMAT_GET_ORDER_STATUS;

        $url = sprintf(
            $format,
            urlencode($this->orderId),
            urlencode($this->password),
            urlencode($this->userName)
        );

        $result = file_get_contents($url);
        $result = json_decode($result);

        switch ($result->OrderStatus) {
            case 1:
                return true;
                break;
            case 2:
                return true;
                break;

            default:
                return false;
                break;
        }

    }

    public function getOrderId() {

        return $this->orderId;
    }
}
