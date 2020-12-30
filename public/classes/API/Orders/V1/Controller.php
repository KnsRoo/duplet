<?php

namespace API\Orders\V1;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;

use Websm\Framework\Di;
use Websm\Framework\Exceptions\InvalidArgumentException;
use Websm\Framework\Exceptions\BaseException;
use Websm\Framework\Exceptions\HTTP as HTTPException;

use Websm\Framework\JWT\Client as JWTClient;
use Websm\Framework\Router\Request\Query;
use Websm\Framework\Mail\HTMLMessage;

class Controller extends Response
{
    private $userId = null;
    private $params = [];

    public function __construct($params)
    {
        $this->params = array_merge($this->params, $params);
    }

    public function getRoutes()
    {
        $group = Router::group();

        $services = new Services\Controller;
        $group->mount('/services', $services->getRoutes());

        $group->addGet('/', function ($req, $next) {
            $protocol = 'http';

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                $protocol = 'https';

            $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

            $routes = [
                'self' => Router::byName('api:orders:v1')
                    ->getAbsolutePath(),
                'orders' => Router::byName('api:orders:v1:orders')
                    ->getAbsolutePath(),
                'services' => Router::byName('api:orders:v1:services')
                    ->getAbsolutePath(),
            ];

            $this->json([
                '_links' => [
                    'self' => [
                        'href' => $origin . $routes['self'],
                    ],
                    'orders' => [
                        'href' => $origin . $routes['orders'],
                    ],
                    'services' => [
                        'href' => $origin . $routes['services'],
                    ],
                ],
            ]);
        })->setName('api:orders:v1');

        $group->addGet('/orders', [$this, 'getOrders'])
            ->setName('api:orders:v1:orders');

        $group->addPost('/orders', [$this, 'parseJWT']);

        $group->addPost('/orders', [$this, 'appendOrder'])
            ->setName('api:orders:v1:orders');

        $group->addPost('/orders/:id', [$this, 'appendOrder'])
            ->setName('api:orders:v1:order');

        $group->addGet('/orders/:id/print', [$this, 'showOrderHtml'])
            ->setName('api:orders:v1:order:print');

        $group->addGet('/doc/rels/:rel', [$this, 'getRelDoc'])
            ->setName('api:orders:v1:docs');

        return $group;
    }

    public function parseJWT($req, $next)
    {
        $jwt = $_GET['jwt'] ?? null;
        $client = new JWTClient;

        try {
            if ($jwt !== null) {
                $payload = '';
                try {
                    $payload = $client->decodeToken($jwt);
                } catch (HTTPException $e) {
                    throw new HTTPException('authorization failed', 409);
                }

                $payload = json_decode($payload);

                if ($payload->site !== $_SERVER['HTTP_HOST']) {
                    throw new HTTPException('authorization failed', 409);
                }
                $user = \Model\User::find(['id' => $payload->userId])
                    ->get();

                if ($user->isNew()) {
                    throw new HTTPException('user not found', 404);
                }
                $this->userId = $user->id;
            }

            $next();
        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());

            $this->hal([
                'errors' => [
                    ['message' => $e->getMessage()],
                ]
            ]);
        }
    }

    public function getOrders($req, $next)
    {
        $this->hal([
            'validationSchema' => $this->params['validationScheme'],
            '_links' => [
                'self' => [
                    'href' => Router::byName('api:orders:v1:orders')->getURL(),
                ],
            ],
        ]);
    }

    public function appendOrder($req, $next)
    {
        try {
            $body = file_get_contents('php://input');

            $bodyArr = json_decode($body, true);
            $paymentType = &$bodyArr['Способ оплаты'];

            if ($paymentType !== 'При получении')
                throw new HTTPException('unable to process payment type', 422);

            $props = Factory\Filters\Body::filterProps($body, [
                'validationScheme' => $this->params['validationScheme'],
            ]);

            $order = new \Model\Order;
            $order->user_id = $this->userId;
            $order->props = $props;

            $props = json_decode($order->props, true);
            $props['Статус'] = [
                'type' => 'string',
                'value' => 'принят',
            ];

            $order->props = json_encode($props);

            if (!$order->save())
                throw new BaseException('unable to save order');

            $orderId = \Model\Order::getDb()
                ->lastInsertId();

            $order = \Model\Order::find(['id' => $orderId])
                ->get();

            $result = Factory\HAL\Order::get(['item' => $order]);

            /* $this->sendReceiptMail($order->id); */
            /* $this->sendReceiptAdminMail($order->id); */
            $di = Di\Container::instance();
            $cart = $di->get('cart');
            $cart->clear();

            $this->hal($result);
        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    ['message' => $e->getMessage()],
                ],
            ]);
        } catch (InvalidArgumentException $e) {

            $this->code(422);
            $this->json([
                'errors' => [
                    ['message' => $e->getMessage()],
                ],
            ]);
        } catch (BaseException $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    ['message' => $e->getMessage()],
                ],
            ]);
        }
    }

    public function showOrderHtml($req, $next)
    {
        $token = &$_GET['jwt'];
        $client = new JWTClient;

        try {
            $content = json_decode($client->decodeToken($token));

            if ($content->site !== $_SERVER['HTTP_HOST']) {
                throw new HTTPException('wrong domain', 409);
            }
            if (!property_exists($content, 'order_id')) {
                throw new HTTPException('invalid order id', 409);
            }
            if ((int)$req['id'] !== (int)$content->order_id) {
                throw new HTTPException('invalid order id', 409);
            }
        } catch (HTTPException $e) {

            $code = $e->getHttpCode();
            $this->code($e->getCode());
            $this->json([
                'errors' => [
                    ['message' => $e->getMessage()],
                ]
            ]);
        }

        $order = \Model\Order::find(['id' => $req['id']])
            ->get();

        if ($order->isNew()) {
            $this->code(404);
            $this->json([
                'errors' => [
                    ['message' => 'order not found'],
                ]
            ]);
        }

        $data = [
            'order' => $order,
        ];

        $html = $this->render(__DIR__ . '/temp/order-print.tpl', $data);
        die($html);
    }

    private function sendReceiptMail($orderId)
    {
        $proto = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
        $path = Router::byName('api:orders:v1:order:print')
            ->getAbsolutePath(['id' => $orderId]);

        $payload = [
            'order_id' => $orderId,
            'site' => $_SERVER['HTTP_HOST'],
        ];

        $client = new JWTClient;
        $tokens = $client->getTokens($payload);
        $accessToken = $tokens->accessToken;

        $query = new Query(['jwt' => '']);
        $query->jwt = $accessToken;

        $url = $proto . '://' . $_SERVER['HTTP_HOST'] . $path . $query;

        /* die($url); */
        $hash = sha1($url);
        $tokens = $client->getTokens(['sha1' => $hash]);

        $accessToken = $tokens->accessToken;
        $query = new Query(['href' => '']);
        $query->href = $url;
        $query->jwt = $accessToken;

        $ref = 'http://html2pdf.websm.io/generator' . $query;

        $ch = curl_init($ref);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $output = curl_exec($ch);

        $fileName = tempnam(sys_get_temp_dir(), uniqid());
        $fd = fopen($fileName, 'w');
        fwrite($fd, $output);
        fclose($fd);

        curl_close($ch);

        $order = \Model\Order::find(['id' => $orderId])
            ->get();

        $props = json_decode($order->props, true);
        $status = $props['Статус']['value'];
        $domain = $_SERVER['SERVER_NAME'];
        $body = '<h3>Статус вашего заказа:</h3><h4>' . $status . '</h4>';

        $emails = &$props['Электронные почты']['value'];

        foreach ($emails as $email) {
            $mail = new HTMLMessage;
            $mail->setFrom('<noreply@websm.io>')
                ->setTo('<' . $email . '>')
                ->setSubject('Заказ с сайта ' . $domain)
                ->setBody($body)
                ->addfile($fileName, 'bill.pdf');

            $mail->send();
        }

        unlink($fileName);
    }

    private function sendReceiptAdminMail($orderId)
    {
        $order = \Model\Order::find(['id' => $orderId])
            ->get();

        $emails = $this->params['emails'];

        if (!is_array($emails)) {
            throw new \Exception('invalid emails specified');
        }
        $body = $this->render(__DIR__ . '/temp/order-admin.tpl', [
            'order' => $order,
        ]);

        $domain = $_SERVER['SERVER_NAME'];

        foreach ($emails as $email) {
            $mail = new HTMLMessage;
            $mail->setFrom('<noreply@websm.io>')
                ->setTo('<' . $email . '>')
                ->setSubject('Новый заказ на сайте ' . $domain)
                ->setBody($body);

            $mail->send();
        }
    }

    public function getRelDoc($req, $next)
    {
        $validRels = ['orders'];
        $validAccepts = ['text/html', 'text/yaml'];

        $headers = apache_request_headers();
        $accepts = &$headers['Accept'];
        $accepts = (string) $accepts;
        $accepts = explode(',', $accepts);
        $accept = 'text/yaml';

        foreach ($accepts as $acceptItem)
            if (in_array($acceptItem, $validAccepts)) {
                $accept = $acceptItem;
                break;
            }

        $rel = $req['rel'];
        if (!in_array($rel, $validRels)) {
            $this->code(404);
            die();
        }

        switch ($accept) {
            case 'text/html':
                header('Content-Type: text/html');
                die(file_get_contents(__DIR__ . "/docs/${rel}.html"));
                break;
            case 'text/yaml':
                header('Content-Type: text/yaml');
                die(file_get_contents(__DIR__ . "/docs/${rel}.yml"));
                break;
        }
    }
}
