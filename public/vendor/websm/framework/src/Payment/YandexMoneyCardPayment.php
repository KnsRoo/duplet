<?php

namespace Websm\Framework\Payment;

use Websm\Framework\Payment\Exceptions\PaymentException;
use \YandexMoney\ExternalPayment;
use Core\Router\Request\Query;

/* use Websm\Framework\Exceptions\BaseException; */
/*
 * class to make pay through Yandex.Cash using Yandex API
 *
 *
 * Documentation: https://tech.yandex.ru/money/doc/dg/reference/process-external-payment-docpage/
 */

class YandexMoneyCardPayment implements PaymentInterface {

    /* Application name*/
    const NAME = 'Flagman';

    /* client_id for oauth2 */
    const CLIENT_ID = 'CB9940160BAF8D19051FCD880484C96460FC3AFD0E4D1F4E635788B9C16A3D0B';

    /* client_secret for oauth2 */
    const CLIENT_SECRET = '15171B162ABA00A94BFB0DB2EADEB77E9C7B7197998AA084692291EA779F7132B9B5029DE8A6CF4515ED5E1011E3C29AE94D824DDAAC2DA6A184810397167BB7';

    const ORDER_STATUS_SUCCESS = 'success';
    const ORDER_STATUS_PENDING = 'pending';
    const ORDER_STATUS_REFUSED = 'refused';

    // aplication instance identifier
    private $instanceId = null;

    // payment context identifier
    private $requestId = null;

    // 'p2p' for individuals, $scid for shops
    const PATTERN_ID = 'p2p';

    // yandex cash number
    const TO = '410015799614842'; 

    /* sum to pay */
    private $amount = 0;

    // url for successful 3-D secure authentication
    private $successUrl = null;
    // url for failed 3-D secure authentication
    private $failUrl = null;

    // url for payment
    private $paymentUrl = null;

    private $orderStatus = self::ORDER_STATUS_PENDING;

    private $externalPayment = null;
    private $processOptions = null;

    // time to wait before next request
    private $retry = null;
    /**
     * setAmount 
     *
     * set payment sum in kopeks
     * 
     * @param mixed $amount 
     * @access public
     * @return void
     */
    public function __construct($instanceId = null) {

        $this->instanceId = $instanceId;

        // get instance_id for future requests first if none specified
        if($this->instanceId === null) {

            $response = ExternalPayment::getInstanceId(self::CLIENT_ID);// instance-id

            if($response->status == "success") {

                $this->instanceId = $response->instance_id;

            } else {

                throw new PaymentExeption($response->error);

            }

        }

    }

    /**
     * setAmount 
     *
     * set payment sum in rubles
     * 
     * @param mixed $amount 
     * @access public
     * @return void
     */
    public function setAmount($amount) {

        if(!is_numeric($amount) || $amount < 0)
            throw new PaymentExeption('Error while setting amount');

        $this->amount = $amount;

    }

    /**
     * setSuccessUrl
     *
     * Устанавливает url успешной операции.
     * 
     * @param  $amount
     * @access public
     * @return void
     */
    public function setSuccessUrl($url) {

        $this->successUrl = $url;

    }

    /**
     * setFailUrl
     *
     * Устанавливает url не успешной операции.
     * 
     * @param  $amount
     * @access public
     * @return void
     */
    public function setFailUrl($url) {

        $this->failUrl = $url;

    }

    /**
     * getPaymentUrl
     *
     * Возвращает url операции оплаты.
     * 
     * @access public
     * 
     */
    public function getPaymentUrl() {

        if(!$this->successUrl || !$this->failUrl) {

            throw new PaymentException('initialization error');

        }

        $this->externalPayment = $externalPayment = new ExternalPayment($this->instanceId);

        $paymentOptions = array(
            'pattern_id' => self::PATTERN_ID,
            'to' => SELF::TO,
            'amount_due' => $this->amount,
        );

        $response = $externalPayment->request($paymentOptions);

        if($response->status == "success") {

            // identifier of payment context
            $this->requestId = $response->request_id;

        } else {

            throw new PaymentException($response->error);

        }

        $response = $externalPayment->request($paymentOptions);

        $this->processOptions = $processOptions = array(
            'request_id' => $this->requestId,
            'ext_auth_success_uri' => $this->successUrl,
            'ext_auth_fail_uri' => $this->failUrl,
            // other params..
        );

        $result = $externalPayment->process($processOptions);
        $status = $result->status;

        /*
        if($status == 'success') {
            //not accessible state

            $this->orderStatus = self::ORDER_STATUS_SUCCESS;

        } 
         */
        if($status == 'ext_auth_required') {

            $query = new Query;
            $query->cps_context_id = $result->acs_params->cps_context_id;
            $query->paymentType = $result->acs_params->paymentType;
            $this->paymentUrl = $result->acs_uri . $query;

            return $this->paymentUrl;

        } elseif($status == "refused") {

            throw new PaymentException($response->error);

        } elseif($status == "in_progress") {

            $this->retry = $result->next_retry;
            return null;

        }

        throw new PaymentException('failed to get payment context id');

    }

    /**
     * retry
     *
     * returns time to wait before request repeat
     * 
     * @access public
     * @return numeric
     * 
     */
    public function getRetryTime() {

        return $this->retry;

    }

    /**
     * orderStatus
     *
     * Производит операцию проверки статуса заказа.
     * 
     * @access public
     * @return boolean
     * 
     */
    public function getOrderStatus() {

        if($this->orderStatus == self::ORDER_STATUS_PENDING) {

            $externalPayment = $this->externalPayment;

            $processOptions = $this->processOptions;

            if(!$externalPayment || !$processOptions) 
                throw new PaymentException('order does not exist');

            $result = $externalPayment->process($processOptions);
            $status = $result->status;

            if($status = "success") {

                $this->orderStatus = self::ORDER_STATUS_SUCCESS;

            } elseif ($status = "refused") {

                $this->orderStatus = self::ORDER_STATUS_REFUSED;

            }

        }

        return $this->orderStatus;

    }

    /**
     * getOrderId 
     *
     * Получает идентификатор заказа.
     * 
     * @access public
     * @return void
     */
    public function getOrderId() {

        return $this->requestId;

    }

}
