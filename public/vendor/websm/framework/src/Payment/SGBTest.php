<?php

namespace Websm\Framework\Payment;

use Websm\Framework\Payment\Exceptions\PaymentException;

class SGBTest extends SberBank {

    const PAYMENT_URL = 'https://web.rbsuat.com/sgb/rest/register.do';
    const ORDER_STATUS_URL = 'https://web.rbsuat.com/sgb/rest/getOrderStatus.do';

}
