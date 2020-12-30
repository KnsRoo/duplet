<?php

namespace Websm\Framework\Payment;

use Websm\Framework\Payment\Exceptions\PaymentException;

class SberBankTest extends SberBank {

    const PAYMENT_URL = 'https://3dsec.sberbank.ru/payment/rest/register.do';
    const ORDER_STATUS_URL = 'https://3dsec.sberbank.ru/payment/rest/getOrderStatus.do';

}
