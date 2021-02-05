<?php

namespace API\Cart\V1;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;

use Websm\Framework\Exceptions\BaseException;
use Websm\Framework\Di;
use Model\Catalog\Product;
use Websm\Framework\Exceptions\HTTP as HTTPException;
use Websm\Framework\Exceptions\NotFoundException;

use Rs\Json\Patch;
use Rs\Json\Patch\InvalidPatchDocumentJsonException;
use Rs\Json\Patch\InvalidTargetDocumentJsonException;
use Rs\Json\Patch\InvalidOperationException;
use Websm\Framework\Cart\Item;

use Model\Catalog\Group;
use Model\Catalog\Childs;

class Controller extends Response
{
    public function __construct()
    {
        $di = Di\Container::instance();
        $this->cart = $di->get('cart');
    }

    public function getRoutes()
    {
        $group = Router::group();

        $group->addGet('/', function ($req, $next) {

            $routes = [
                'self' => Router::byName('api:cart:v1')
                    ->getAbsolutePath(),
                'items' => Router::byName('api:cart:v1:items')
                    ->getAbsolutePath(),
            ];

            $protocol = 'http';

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                $protocol = 'https';

            $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

            $this->hal([
                '_links' => [
                    'self' => [
                        'href' => $origin . $routes['self'],
                    ],
                    'items' => [
                        'href' => $origin . $routes['items'],
                    ],
                ],
            ]);
        })->setName('api:cart:v1');

        $group->addGet('/items', [$this, 'getItems'])
            ->setName('api:cart:v1:items');

        $group->addGet('/count', [$this, 'getCount'])
            ->setName('api:cart:v1:count');

        $group->addPost('/items', [$this, 'appendItem']);

        $group->addGet('/items/:id', [$this, 'getItem'])
            ->setName('api:cart:v1:item');

        $group->add('PATCH', '/items/:id', [$this, 'updateItem']);

        $group->addDelete('/items/:id', [$this, 'deleteItem']);

        $group->addGet('/doc/rels/:rel', [$this, 'getRelDoc'])
            ->setName('api:cart:v1:docs');

        return $group;
    }

    private function getItemsStatus($items){

        $statuses = [];
        $specialArray = [];
        $speacialGroups = Group::byTags(['Бронирование'])
            ->getAll();

        foreach ($speacialGroups as $group) {
            $childs = Childs::find(['id' => $group->id])->get();
            $arr = $childs->getChildArray();
            $specialArray = array_merge($specialArray,$arr);
        }

        foreach ($items as $item) {
            $statuses[$item->product->id] = (in_array($item->product->cid,$specialArray)) ? 'reserved' : 'ready';
        }

        return $statuses;
    }

    public function getCount(){
        try {
            $items = $this->cart->getItems();
            $this->hal(["count" => count($items)]);
        } catch (HTTPException $e){
            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    ['message' => $e->getMessage()],
                ],
            ]);   
        }
    }

    public function getItems($req, $next)
    {
        try {

            $items = $this->cart->getItems();
            $statuses = $this->getItemsStatus($items);
            $result = Factory\HAL\Items::get([
                'items' => $items,
                'statuses' => $statuses
                ]);
            $this->hal($result);
        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    ['message' => $e->getMessage()],
                ],
            ]);
        }
    }

    public function getItem($req, $next)
    {
        try {

            $items = $this->cart->getItems();
            $item = null;
            foreach ($items as $itemIter) {
                if ($itemIter->id == $req['id']) {
                    $item = $itemIter;
                    break;
                }
            }

            if ($item === null)
                throw new HTTPException('cart item not found', 404);

            $result = Factory\HAL\Item::get(['item' => $item]);
            $this->hal($result);
        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    ['message' => $e->getMessage()],
                ],
            ]);
        }
    }

    public function appendItem($req, $next)
    {
        try {

            $body = file_get_contents('php://input');
            $body = json_decode($body);

            if (!property_exists($body, 'id'))
                throw new HTTPException('product id not specified', 422);

            $id = $body->id;

            if (property_exists($body, 'count'))
                $count = (int) $body->count;

            if ($this->cart->has($id))
                throw new HTTPException('product already in cart', 409);

            $item = new Item($id);

            try {
                foreach ($body as $key => $value)
                    if ($key !== 'id')
                        $item->$key = $value;
            } catch (BaseException $e) {

                throw new HTTPException($e->getMessage(), 422);
            }

            $product = Product::find(['id' => $id, 'visible' => true])
                ->get();

            if ($product->isNew())
                throw new HTTPException('product not found', 422);

            $item->title = $product->title;
            $item->price = $product->price;
            $item->preview = $product->preview;
            $item->about = $product->about;
            $item->product = $product;

            $productRoute = Router::byName('catalog:product');
            $path = $productRoute->getAbsolutePath(['chpu' => $product->chpu]);

            $protocol = 'http';

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                $protocol = 'https';

            $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];
            $item->pageRef = $origin . $path;

            $item->picture = $product->getPicture('700x700');

            $this->cart->add($item);

            $result = Factory\HAL\Item::get(['item' => $item]);
            $this->hal($result);
        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    ['message' => $e->getMessage()],
                ],
            ]);
        }
    }

    public function updateItem($req, $next)
    {
        try {

            $patchDoc = file_get_contents('php://input');
            if (!$this->cart->has($req['id']))
                throw new HTTPException('cart item not found', 404);

            $item = $this->cart->getItem($req['id']);
            $itemArr = $item->asArray();
            $doc = json_encode($itemArr);

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

            if ($patchedDoc->id != $item->id)
                throw new HTTPException('id is read only', 403);
            unset($patchedDoc->id);

            if ($patchedDoc->price != $item->price)
                throw new HTTPException('price is read only', 403);
            unset($patchedDoc->price);

            foreach ($patchedDoc as $key => $value)
                $item->$key = $value;

            die();
        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    ['message' => $e->getMessage()],
                ],
            ]);
        }
    }

    public function deleteItem($req, $next)
    {
        try {

            try {
                $this->cart->remove($req['id']);
            } catch (NotFoundException $e) {
                throw new HTTPException('cart item not found', 404);
            }

            die();
        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    ['message' => $e->getMessage()],
                ],
            ]);
        }
    }

    public function getRelDoc($req, $next)
    {
        $validRels = ['cart'];
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
