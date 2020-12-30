<?php

namespace API\Orders\V1\Services;

use API\Orders\V1\Factory;
use Websm\Framework\Response;
use Websm\Framework\Router\Router;

use Websm\Framework\Di;
use Websm\Framework\Exceptions\InvalidArgumentException;
use Websm\Framework\Exceptions\BaseException;
use Websm\Framework\Exceptions\HTTP as HTTPException;

class Sberbank extends Response {

    private $userId = null;

    public function hal($data) {

        header('Content-Type: application/hal+json');
        die(json_encode($data));
    }

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', function($req, $next) {

            $protocol = 'http';

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                $protocol = 'https';

            $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

            $routes = [
                'self' => Router::byName('api:orders:v1:services:sberbank')
                    ->getAbsolutePath(),
                'ops' => Router::byName('api:orders:v1:services:sberbank:ops')
                    ->getAbsolutePath(),
            ];

            $this->json([
                '_links' => [
                    'self' => [
                        'href' => $origin . $routes['self'],
                    ],
                    'ops' => [
                        'href' => $origin . $routes['ops'],
                    ],
                ],
            ]);
        })->setName('api:orders:v1:services:sberbank');

        $group->addPost('/ops', [$this, 'verifyToken']);
        $group->addPost('/ops', [$this, 'appendOrder'])
            ->setName('api:orders:v1:services:sberbank:ops');

        $group->addGet('/ops/:id/ok', [$this, 'getOrderOk'])
            ->setName('api:orders:v1:services:sberbank:ops:ok');

        $group->addGet('/ops/:id/err', [$this, 'getOrderErr'])
            ->setName('api:orders:v1:services:sberbank:ops:err');

        return $group;
    }

    public function verifyToken($req, $next) {

        $headers = array_change_key_case(apache_request_headers());
        $auth = &$headers['authorization'];

        $matches = [];
        preg_match('/^Bearer (.*)$/', $auth, $matches);

        if (isset($matches[1])) {
            $token = $matches[1];

            $client = new JWTClient;

            try {

                $content = json_decode($client->decodeToken($token));
                if (key_exists($content, 'user_id'))
                    $this->userId = $content->user_id;

                if ($content->site !== $_SERVER['SERVER_NAME'])
                    throw new HTTPException('wrong domain', 409);

            } catch(HTTPException $e) {
                $code = $e->getHttpCode();
                $this->code($e->getCode());
                $this->json([
                    'errors' => [
                        ['message' => $e->getMessage()],
                    ]
                ]);
            }
        }

        $next();
    }

    public function appendOrder($req, $next) {

        try {

            $body = file_get_contents('php://input');

            $bodyArr =json_decode($body, true);
            $paymentType = $bodyArr['Способ оплаты'];

            if ($paymentType !== 'Онлайн')
                throw new HTTPException('unable to process payment type', 422);

            $props = Factory\Filters\Body::filterProps($body);

            $order = new \Model\Order;
            $order->user_id = $this->userId;
            $order->props = $props;

            $di = Di\Container::instance();
            $redis = $di->get('redis');
            $sberbank = $di->get('sberbank');
            $cart = $di->get('cart');

            $opId = sha1(uniqid());
            $key = 'order:' . $opId;
            $value = serialize($order);
            $redis->set($key, $value);
            $redis->setTimeout($key, 60*30);

            $routes = [
                'ok' => Router::byName('api:orders:v1:services:sberbank:ops:ok')
                    ->getAbsolutePath([ 'id' => $opId ]),
                'err' => Router::byName('api:orders:v1:services:sberbank:ops:err')
                    ->getAbsolutePath([ 'id' => $opId ]),
            ];

            $origin = 'https://' . $_SERVER['SERVER_NAME'];

            $sberbank->setAmount($cart->getSumm())
                ->setSuccessUrl($origin . $routes['ok'])
                ->setFailUrl($origin . $routes['err']);

            $url = $sberbank->getPaymentUrl();

            $key = 'online-payment:' . $opId;
            $value = serialize($sberbank);
            $redis->set($key, $value);
            $redis->setTimeout($key, 60*30);

            $this->hal([
                '_links' => [
                    'self' => [
                        'href' => $origin . Router::byName('api:orders:v1:services:sberbank')
                            ->getAbsolutePath(),
                    ],
                    'payment' => [
                        'href' => $url,
                    ],
                ],
            ]);

        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    ['message' => $e->getMessage()],
                ],
            ]);
        }
    }

    public function getOrderOk($req, $next) {

        try {

            $opId = $req['id'];
            $di = Di\Container::instance();

            $redis = $di->get('redis');

            $key = 'online-payment:' . $opId;
            $sberbank = unserialize($redis->get($key));

            $key = 'order:' . $opId;
            $order = unserialize($redis->get($key));

            if (!$sberbank || !$order)
                throw new HTTPException('Время ожидания оплаты истекло', 400);

            if ($sberbank->getOrderStatus()) {

                $props = json_decode($order->props, true);
                $props['Статус'] = [
                    'type' => 'string',
                    'value' => 'оплачен',
                ];

                $order->props = json_encode($props);

                if (!$order->save())
                    throw new HTTPException('Не удалось сохранить заказ', 500);

                $cart = $di->get('cart');
                $cart->clear();
                $redis->delete($keyPayment);
                $redis->delete($keyOrder);

            } else {
                throw new HTTPException('Заказ не оплачен', 400);
            }

            $this->location('/');

        } catch (HTTPException $e) {

            $this->location('/error?message=' . $e->getMessage() . '&code=' . $e->getHttpCode());
        }
    }

    public function getOrderErr($req, $next) {

        try {

            $opId = $req['id'];
            $di = Di\Container::instance();

            $redis = $di->get('redis');

            $keyPayment = 'online-payment:' . $opId;
            $sberbank = unserialize($redis->get($keyPayment));

            $keyOrder = 'order:' . $opId;
            $order = unserialize($redis->get($keyOrder));

            $redis->delete($keyPayment);
            $redis->delete($keyOrder);

            throw new HTTPException('Заказ не был оплачен', 400);

        } catch (HTTPException $e) {

            $this->location('/error?message=' . $e->getMessage() . '&code=' . $e->getHttpCode());
        }
    }
}
