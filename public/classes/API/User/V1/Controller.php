<?php

namespace API\User\V1;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;

use Websm\Framework\JWT\Client as JWTClient;
use Websm\Framework\Exceptions\HTTP as HTTPException;

use Rs\Json\Patch;
use Rs\Json\Patch\InvalidPatchDocumentJsonException;
use Rs\Json\Patch\InvalidTargetDocumentJsonException;
use Rs\Json\Patch\InvalidOperationException;

use Websm\Framework\Di;

use Model\Catalog\Favorite;

class Controller extends Response {

    private $user;

    public function getRoutes() {

        $group = Router::group();

        $group->addAll('/', [ $this, 'parseJWT' ], [ 'end' => false ])
            ->setName('api:user:v1');

        $group->addGet('/', [ $this, 'getUser']);

        $group->addPut('/email', [ $this, 'setEmail'])
            ->setName('api:user:v1:email');

        $group->addPut('/phone', [ $this, 'setPhone'])
            ->setName('api:user:v1:phone');

        $group->addGet('/props', [ $this, 'getProps'])
            ->setName('api:user:v1:props');

        $group->addGet('/orders', [ $this, 'getOrders'])
            ->setName('api:user:v1:orders');

        $group->addGet('/orders/:id', [ $this, 'getOrder'])
            ->setName('api:user:v1:order');

        $group->add('PATCH', '/props', [ $this, 'patchProps']);

        return $group;
    }

    public function parseJWT($req, $next) {

        $jwt = $_GET['jwt'] ?? '';
        $client = new JWTClient;

        try {

            $payload = '';
            try {

                $payload = $client->decodeToken($jwt);
            } catch (HTTPException $e) {

                throw new HTTPException('authorization failed', 409);
            }

            $payload = json_decode($payload);
            if ($payload->site !== $_SERVER['HTTP_HOST'])
                throw new HTTPException('authorization failed', 409);

            $user = \Model\User::find(['id' => $payload->userId])
                ->get();

            if ($user->isNew())
                throw new HTTPException('user not found', 404);

            $this->user = $user;
            $next();

        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());

            $this->hal([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ]
            ]);
        }
    }

    public function getUser($req, $next) {

        $result = Factory\HAL\User::get([
            'item' => $this->user
        ]);

        $this->hal($result);
    }

    public function getProps($req, $next) {

        $result = Factory\HAL\Props::get([
            'items' => (Object)json_decode($this->user->props),
        ]);

        $this->hal($result);
    }

    public function patchProps($req, $next) {

        try {

            $user = $this->user;
            $doc = $user->props;
            $doc = json_encode((Object)json_decode($doc));

            $patchDoc = file_get_contents('php://input');

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

            $patchedDoc = json_decode($patchedDoc);

            $user->props = json_encode($patchedDoc);

            if (!$user->save())
                throw new HTTPException('Не удалось сохранить продукт', 500);

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

    public function setEmail($req, $next) {

        try {

            $body = file_get_contents('php://input');
            $bodyObj = (Object)json_decode($body);

            if (!property_exists($bodyObj, 'email'))
                throw new HTTPException('invalid email specified', 422);

            $user = $this->user;
            $di = Di\Container::instance();
            $crypt = $di->get('crypt');
            $user->email = $crypt->encrypt($bodyObj->email);

            if (!$user->save())
                throw new HTTPException('invalid email specified', 422);

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

    public function setPhone($req, $next) {

        try {

            $body = file_get_contents('php://input');
            $bodyObj = (Object)json_decode($body);

            if (!property_exists($bodyObj, 'phone'))
                throw new HTTPException('invalid phone specified', 422);

            $user = $this->user;
            $di = Di\Container::instance();
            $crypt = $di->get('crypt');
            $user->phone = $crypt->encrypt($bodyObj->phone);

            if (!$user->save())
                throw new HTTPException('invalid phone specified', 422);

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

    public function getOrders($req, $next) {

        $user = $this->user;

        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $order = Factory\QueryParams::getOrder();

        $qb = \Model\Order::find([ 'user_id' => $user->id ]);
        $qb = Factory\Filters\QB\OrderOrders::filter($qb, $order);

        $qbCnt = clone $qb;

        $orders = $qb->limit([ $offset, $limit ])
            ->getAll();

        $total = (Integer)$qbCnt->count();

        $result = Factory\HAL\Orders::get([
            'items' => $orders,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total,
        ]);

        $this->hal($result);
    }

    public function getOrder($req, $next) {

        $order = \Model\Order::find([ 
            'user_id' => $this->user->id,
            'id' => $req['id'],
        ])->get();

        try {

            if ($order->isNew())
                throw new HTTPException('order not found', 404);

        } catch(HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ],
            ]);
        }

        $result = Factory\HAL\Order::get([
            'item' => $order,
        ]);

        $this->hal($result);
    }
}
