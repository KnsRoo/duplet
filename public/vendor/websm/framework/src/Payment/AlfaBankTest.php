<?php

namespace Websm\Framework\Payment;

use Websm\Framework\Payment\Exceptions\PaymentException;

class AlfaBankTest extends SberBank {

    const PAYMENT_URL = 'https://web.rbsuat.com/ab/rest/register.do';
    const ORDER_STATUS_URL = 'https://web.rbsuat.com/ab/rest/getOrderStatus.do';

}
