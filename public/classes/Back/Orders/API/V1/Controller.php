<?php

namespace Back\Orders\API\V1;

use Websm\Framework\Router\Router;
use Websm\Framework\Response;

use Model\Catalog\Product;

use Websm\Framework\Di;
use Websm\Framework\Exceptions\HTTP as HTTPException;

use Websm\Framework\JWT\Client as JWTClient;
use Websm\Framework\Router\Request\Query;
use Websm\Framework\Mail\HTMLMessage;

use Rs\Json\Patch;
use Rs\Json\Patch\InvalidPatchDocumentJsonException;
use Rs\Json\Patch\InvalidTargetDocumentJsonException;
use Rs\Json\Patch\InvalidOperationException;

class Controller extends Response
{

    public function getRoutes()
    {
        $group = Router::group();

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
            ];

            $this->hal([
                '_links' => [
                    'self' => [
                        'href' => $origin . $routes['self'],
                    ],
                    'orders' => [
                        'href' => $origin . $routes['orders'],
                    ],
                ],
            ]);
        })->setName('api:orders:v1');

        $group->addGet('/orders', [$this, 'getOrders'])
            ->setName('api:orders:v1:orders');

        $group->addGet('/orders/:id', [$this, 'getOrder'])
            ->setName('api:orders:v1:order');

        $group->addGet('/orders/:id/props', [$this, 'getOrderProps'])
            ->setName('api:orders:v1:order:props');

        $group->addPost('/orders/:id/props', [$this, 'appendOrderProp']);

        $group->addPost('/orders/:id/printref', [$this, 'getOrderPrintRef'])
            ->setName('api:orders:v1:order:printref');

        $group->addPost('/orders/:id/mailsender', [$this, 'sendEmail'])
            ->setName('api:orders:v1:order:mailsender');

        $group->addPost('/orders/:id/smssender', [$this, 'sendSms'])
            ->setName('api:orders:v1:order:smssender');

        $group->add('PATCH', '/orders/:id/props/:name', [$this, 'updateOrderProp'])
            ->setName('api:orders:v1:order:prop');

        $group->addDelete('/orders/:id/props/:name', [$this, 'removeOrderProp']);

        $group->addDelete('/orders/:id', [$this, 'removeOrder']);

        return $group;
    }

    public function getOrders($req, $next)
    {
        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();

        $qb = \Model\Order::find();
        $qbCnt = clone $qb;

        $orders = $qb->order('JSON_EXTRACT(props, \'$."Дата".value\') DESC')
            ->limit([$offset, $limit])
            ->getAll();

        $total = (int) $qbCnt->count();

        $result = Factory\HAL\Orders::get([
            'items' => $orders,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total,
        ]);

        $this->hal($result);
    }

    public function getOrder($req, $next)
    {
        try {

            $order = \Model\Order::find(['id' => $req['id']])
                ->get();

            if ($order->isNew())
                throw new HTTPException('order not found', 404);

            $result = Factory\HAL\Orders::get(['item' => $order]);
            $this->hal($result);
        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->hal([
                'errors' => [
                    ['message' => $e->getMessage()],
                ]
            ]);
        }
    }

    public function getOrderProps($req, $next)
    {
        try {

            $order = \Model\Order::find(['id' => $req['id']])
                ->get();

            if ($order->isNew())
                throw new HTTPException('order not found', 404);

            $result = Factory\HAL\Props::get(['item' => $order]);
            $this->hal($result);
        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->hal([
                'errors' => [
                    ['message' => $e->getMessage()],
                ]
            ]);
        }
    }

    public function appendOrderProp($req, $next)
    {
        try {

            $order = \Model\Order::find(['id' => $req['id']])
                ->get();

            if ($order->isNew())
                throw new HTTPException('Заказ не найден', 404);

            $body = json_decode(file_get_contents('php://input'), true);
            $name = &$body['name'];
            $type = &$body['type'];

            $props = json_decode($order->props, true);

            if (isset($props[$name]))
                throw new HTTPException('Свойство существует', 409);

            $props[$name] = [
                'type' => $type,
            ];

            $order->props = json_encode($props);

            if (!$order->save())
                throw new HTTPException('Не удалось сохранить заказ', 500);

            $result = Factory\HAL\Prop::get([
                'item' => $order,
                'name' => $name,
            ]);

            $this->hal($result);
        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        }
    }

    public function updateOrderProp($req, $next)
    {
        try {

            $name = urldecode($req['name']);
            $order = \Model\Order::find(['id' => $req['id']])
                ->get();

            if ($order->isNew())
                throw new HTTPException('Заказ не найден', 404);

            $patchDoc = file_get_contents('php://input');

            $props = json_decode($order->props, true);

            if (!isset($props[$name]))
                throw new HTTPException('Свойство не найдено', 404);

            $doc = json_encode($props[$name]);

            $patchedDoc = '';

            try {
                $patch = new Patch($doc, $patchDoc);
                $patchedDoc = $patch->apply();
            } catch (InvalidPatchDocumentJsonException $e) {
                throw new HTTPException($e->getMessage(), 422);
            } catch (InvalidTargetDocumentJsonException $e) {
                throw new HTTPException($e->getMessage(), 500);
            } catch (InvalidOperationException $e) {
                throw new HTTPException($e->getMessage(), 422);
            }

            $props[$name] = json_decode($patchedDoc);
            $order->props = json_encode($props);

            if (!$order->save())
                throw new HTTPException('Не удалось сохранить заказ', 500);

            $result = Factory\HAL\Prop::get([
                'item' => $order,
                'name' => $name,
            ]);

            $this->hal($result);
        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        }
    }

    public function removeOrderProp($req, $next)
    {
        try {

            $name = urldecode($req['name']);
            $order = \Model\Order::find(['id' => $req['id']])
                ->get();

            if ($order->isNew())
                throw new HTTPException('Заказ не найден', 404);

            $name = $req['name'];

            if (!isset($props[$name]))
                throw new HTTPException('Свойство не найдено', 404);

            unset($props[$name]);

            if (!$props)
                $props = (object) null;

            $order->props = json_encode($props);

            if (!$order->save())
                throw new HTTPException('Не удалось сохранить заказ', 500);

            $this->code(204);
            die();
        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        }
    }

    public function removeOrder($req, $next)
    {
        try {

            $order = \Model\Order::find(['id' => $req['id']])
                ->get();

            if ($order->isNew())
                throw new HTTPException('order not found', 404);

            $order->delete();
            $this->code(204);
            die();
        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->hal([
                'errors' => [
                    ['message' => $e->getMessage()],
                ]
            ]);
        }
    }

    public function getOrderPrintRef($req, $next)
    {
        try {

            $order = \Model\Order::find(['id' => $req['id']])
                ->get();

            if ($order->isNew())
                throw new HTTPException('order not found', 404);

            $hash = md5($order->props);
            $proto = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
            $path = '/api/orders/orders/' . $order->id . '/print';

            $payload = [
                'order_id' => $order->id,
                'site' => $_SERVER['HTTP_HOST'],
            ];

            $client = new JWTClient;
            $tokens = $client->getTokens($payload);
            $accessToken = $tokens->accessToken;

            $query = new Query(['jwt' => '', 'hash' => '']);
            $query->jwt = $accessToken;
            $query->hash = $hash;

            $url = $proto . '://' . $_SERVER['HTTP_HOST'] . $path . $query;

            die(json_encode(['url' => $url]));
        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->hal([
                'errors' => [
                    ['message' => $e->getMessage()],
                ]
            ]);
        }
    }

    public function sendEmail($req, $next)
    {
        try {

            $order = \Model\Order::find(['id' => $req['id']])
                ->get();

            if ($order->isNew())
                throw new HTTPException('order not found', 404);

            $proto = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
            $path = '/api/orders/orders/' . $order->id . '/print';

            $payload = [
                'order_id' => $order->id,
                'site' => $_SERVER['HTTP_HOST'],
            ];

            $client = new JWTClient;
            $tokens = $client->getTokens($payload);
            $accessToken = $tokens->accessToken;

            $query = new Query(['jwt' => '']);
            $query->jwt = $accessToken;

            $url = $proto . '://' . $_SERVER['HTTP_HOST'] . $path . $query;

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

            $this->code(200);
            die();
        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->hal([
                'errors' => [
                    ['message' => $e->getMessage()],
                ]
            ]);
        }
    }

    public function sendSms($req, $next)
    {
        try {

            $order = \Model\Order::find(['id' => $req['id']])
                ->get();

            if ($order->isNew())
                throw new HTTPException('order not found', 404);

            $props = json_decode($order->props, true);
            if (!isset($props['Телефоны']))
                throw new HTTPException('phones not found');

            if (!isset($props['Телефоны']['value']))
                throw new HTTPException('phones not found');

            $phones = $props['Телефоны']['value'];

            if (!is_array($phones))
                throw new HTTPException('invalid phones');

            $status = &$props['Статус']['value'];

            $gate = (Di\Container::instance())->get('sms-gate');

            foreach ($phones as $phone) {
                $msg = new \Websm\Framework\Sms\Message($phone, 'Статус вашего заказа: ' . $status);
                $gate->send($msg);
            }

            $this->code(200);
        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->hal([
                'errors' => [
                    ['message' => $e->getMessage()],
                ]
            ]);
        }
    }
}
