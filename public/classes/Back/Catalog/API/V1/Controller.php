<?php

namespace Back\Catalog\API\V1;

use Websm\Framework\Router\Router;

use Core\Users;

use Websm\Framework\Di;

use Rs\Json\Patch;
use Rs\Json\Patch\InvalidPatchDocumentJsonException;
use Rs\Json\Patch\InvalidTargetDocumentJsonException;
use Rs\Json\Patch\InvalidOperationException;

class Controller extends \Websm\Framework\Response {

    const FORCED_SCHEME = 'http';
    const BASE_PATH = '/admin/catalog/api/v1';

    public function __construct() {

        /* $this->origin = self::FORCED_SCHEME . '://'. $_SERVER['SERVER_NAME']; */
        $this->origin = '';
        $this->basePath = $this->origin . self::BASE_PATH;
    }

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', function($req, $next) {

            $this->hal([
                '_links' => [
                    'self' => [
                        'href' => $this->origin . $_SERVER['REQUEST_URI'],
                    ],
                    'products' => [
                        'href' => $this->basePath . "/products",
                    ],
                ],
            ]);
        });

        $group->addGet('/products', [$this, 'getProducts']);
        $group->addGet('/products/:id', [$this, 'getProduct']);
        $group->addGet('/products/:id/props', [ $this, 'getProps' ]);
        $group->addPost('/products/:id/props', [ $this, 'createProp' ]);
        $group->addGet('/products/:id/props/:prop', [ $this, 'getProp' ]);
        $group->add('PATCH', '/products/:id/props/:prop', [ $this, 'updateProp' ]);
        $group->addPut('/products/:id/props/:prop', [ $this, 'replaceProp' ]);
        $group->addDelete('/products/:id/props/:prop', [ $this, 'removeProp' ]);

        $group->addGet('/tags', [$this, 'getTags'])
            ->setName('catalog:api:v1:tags');

        $group->addGet('/tags/:id', [$this, 'getTag'])
            ->setName('catalog:api:v1:tag');

        $group->addAll('/', [$this, 'notFound'], [ 'end' => false ]);

        return $group;
    }

    public function notFound($req, $next) {

        $this->code(404);
        $this->json([
            'errors' => [
                [ 'message' => 'route not found' ],
            ],
        ]);

    }

    public function getProps($req, $next) {

        try {

            $product = \Model\Catalog\Product::find(['id' => $req['id']])
                ->get();

            if ($product->isNew())
                throw new Exceptions\HTTP('Продукт не найден', 404);

            $props = json_decode($product->props);

            if (!is_object($props))
                $props = (Object)[];

            $this->hal([
                '_links' => Factory\HAL\Props::getLinks([
                    'origin' => $this->origin,
                    'basePath' => $this->basePath,
                    'productId' => $req['id'],
                    'items' => $props,
                ]),
                '_embedded' => Factory\HAL\Props::getEmbedded([
                    'origin' => $this->origin,
                    'basePath' => $this->basePath,
                    'productId' => $req['id'],
                    'items' => $props,
                ]),
            ]);

        } catch (Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } catch (\Exception $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } 
    }

    public function createProp($req, $next) {

        try {

            $product = \Model\Catalog\Product::find(['id' => $req['id']])
                ->get();

            if ($product->isNew())
                throw new Exceptions\HTTP('Продукт не найден', 404);

            $body = json_decode(file_get_contents('php://input'));

            if (!property_exists($body, 'type'))
                throw new Exceptions\HTTP('Тип не задан', 422);

            $type = trim($body->type);
            if (!is_string($type) || !$type)
                throw new Exceptions\HTTP('Не корректный тип', 422);

            if (!property_exists($body, 'name'))
                throw new Exceptions\HTTP('Имя не задано', 422);

            $name = trim($body->name);
            if (!$name || ! is_string($name))
                throw new Exceptions\HTTP('Не корректное имя свойства', 422);

            $props = (Object)json_decode($product->props);

            if (property_exists($props, $name))
                throw new Exceptions\HTTP('Свойство существует', 409);

            $props->$name = (Object)null;
            $props->$name->type = $type;

            $product->props = json_encode($props);

            if ($product->save()) {

                \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                    '<b>'.Users::get()->login.'</b> создал свойство "'.$name.'" товара "'.$product->title.'".',
                    'Каталог');

                $this->code(201);
                $this->hal([
                    'name' => $name,
                    'content' => [
                        'type' => $props->$name->type,
                    ],
                    '_links' => Factory\HAL\Prop::getLinks([
                        'origin' => $this->origin,
                        'basePath' => $this->basePath,
                        'productId' => $req['id'],
                        'name' => $name,
                        'item' => $props->$name,
                    ]),
                    '_embedded' => Factory\HAL\Prop::getEmbedded([
                        'origin' => $this->origin,
                        'basePath' => $this->basePath,
                        'productId' => $req['id'],
                        'name' => $name,
                        'item' => $props->$name,
                    ]),
                ]);

            } else throw new Exceptions\HTTP('Не удалось сохранить продукт', 500);

        } catch (Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } catch (\Exception $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } 

    }

    public function getProp($req, $next) {

        try {

            $product = \Model\Catalog\Product::find(['id' => $req['id']])
                ->get();

            if ($product->isNew())
                throw new Exceptions\HTTP('Продукт не найден', 404);

            $props = json_decode($product->props);
            $name = $req['prop'];

            if ($props == null)
                $props = (object)[];

            if (!isset($props->$name))
                throw new Exceptions\HTTP('Свойство не найдено', 404);

            $this->hal([
                'name' => $name,
                'content' => $props->$name,
                '_links' => Factory\HAL\Prop::getLinks([
                    'origin' => $this->origin,
                    'basePath' => $this->basePath,
                    'productId' => $req['id'],
                    'name' => $name, 
                    'item' => $props->$name,
                ]),
                '_embedded' => Factory\HAL\Prop::getEmbedded([
                    'origin' => $this->origin,
                    'basePath' => $this->basePath,
                    'productId' => $req['id'],
                    'name' => $name, 
                    'item' => $props->$name,
                ]),
            ]);

        } catch (Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } catch (\Exception $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } 
    }

    public function updateProp($req, $next) {

        try {

            $product = \Model\Catalog\Product::find(['id' => $req['id']])
                ->get();

            if ($product->isNew())
                throw new Exceptions\HTTP('Продукт не найден', 404);

            $name = $req['prop'];

            $props = json_decode($product->props);

            if (!property_exists($props, $name))
                throw new Exceptions\HTTP('Свойство не найдено', 404);

            $prop = $props->$name;
            $doc = json_encode($prop);

            $patchDoc = file_get_contents('php://input');

            $patchedDoc = '';

            try {
                $patch = new Patch($doc, $patchDoc);
                $patchedDoc = $patch->apply();
            } catch (InvalidPatchDocumentJsonException $e) {
                throw new Exceptions\HTTP($e->getMessage(), 422);
            } catch (InvalidTargetDocumentJsonException $e) {
                throw new Exceptions\HTTP($e->getMessage(), 500);
            } catch (InvalidOperationException $e) {
                throw new Exceptions\HTTP($e->getMessage(), 422);
            }

            $patchedDoc = json_decode($patchedDoc);

            if (!property_exists($patchedDoc, 'type'))
                throw new Exceptions\HTTP('Не корректный тип свойства', 422);

            if (!$patchedDoc->type || !is_string($patchedDoc->type))
                throw new Exceptions\HTTP('Не корректный тип свойства', 422);

            $props->$name = $patchedDoc;
            $product->props = json_encode($props);

            if ($product->save()) {

                \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                    '<b>'.Users::get()->login.'</b> обновил свойство "'.$name.'" товара "'.$product->title.'".',
                    'Каталог');
                die();

            } else throw new Exceptions\HTTP('Не удалось сохранить продукт', 500);

        } catch (Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } catch (\Exception $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } 
    }

    public function removeProp($req, $next) {

        try {

            $product = \Model\Catalog\Product::find(['id' => $req['id']])
                ->get();

            if ($product->isNew())
                throw new Exceptions\HTTP('Продукт не найден', 404);

            $name = $req['prop'];

            $props = json_decode($product->props);

            if (!property_exists($props, $name))
                throw new Exceptions\HTTP('Свойство не найдено', 404);

            unset($props->$name);

            $product->props = json_encode($props);

            if ($product->save()) {

                 \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                    '<b>'.Users::get()->login.'</b> удалил свойство "'.$name.'" товара "'.$product->title.'".',
                    'Каталог');

                $this->code(204);
                die();

            } else throw new Exceptions\HTTP('Не удалось удалить свойство', 500);

        } catch (Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } catch (\Exception $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } 
    }

    public function getProducts($req, $next) {

        $offset = QueryParams::getOffset();
        $limit = QueryParams::getLimit();
        $order = QueryParams::getOrderProducts();
        $props = QueryParams::getProps();
        $tags = QueryParams::getTags();

        $qb = \Model\Catalog\Product::find();

        if (isset($_GET['query']))
            $qb = Factory\Filters\QB::filterQuery($qb, $_GET['query']);

        $qb = Factory\Filters\QB\Props::filter($qb, $props);
        $qb = Factory\Filters\QB\Tags::filter($qb, $tags);

        $qbCnt = clone $qb;

        $products = $qb->order($order)
            ->limit([ $offset, $limit ])
            ->getAll();

        $result = [];

        foreach($products as $product) {

            $picSize = 500;
            $picture = $product->getPicture($picSize . 'x' .$picSize);
            $tags = explode(':', trim($product->tags, ':'));
            if ($tags[0] === "") $tags = [];

            $result[] = [
                'id' => (String)$product->id,
                'title' => (String)$product->title,
                'pageRef' => $this->origin . '/Katalog/' . $product->chpu,
                'code' => (String)$product->code,
                'price' => (float)$product->price,
                'preview' => (String)$product->preview,
                'about' => (String)$product->about,
                'picture' => $picture,
                'tags' => $tags,
                'creationDate' => $product->date,
                'sort' => (String)$product->sort,
                'props' => json_decode($product->props),
            ];
        }

        $total = (Integer)$qbCnt->count();
        $this->hal([
            '_embedded' => Factory\HAL\Products::getEmbedded([
                'origin' => $this->origin,
                'baseUrl' => $this->baseUrl,
                'items' => $result,
            ]),
            '_links' => Factory\HAL\Products::getLinks([
                'origin' => $this->origin,
                'baseUrl' => $this->baseUrl,
                'total' => $total,
                'offset' => $offset,
                'limit' => $limit,
            ]),
            'offset' => $offset,
            'limit' => $limit,
            'size' => (Integer)count($products),
            'total' => $total,
        ]);
    }

    public function getProduct($req, $next) {

        $product = \Model\Catalog\Product::find(['id' => $req['id']])
            ->get();

        $picSize = 500;
        $picture = $product->getPicture($picSize . 'x' .$picSize);

        $result = [
            'id' => (String)$product->id,
            'title' => (String)$product->title,
            'pageRef' => $this->origin . '/Katalog/' . $product->chpu,
            'code' => (String)$product->code,
            'price' => (float)$product->price,
            'preview' => (String)$product->preview,
            'about' => (String)$product->about,
            'picture' => $picture,
            'creationDate' => $product->date,
            'sort' => (String)$product->sort,
            'props' => json_decode($product->props),
        ];

        $result['_embedded'] = Factory\HAL\Product::getEmbedded([
                'origin' => $this->origin,
                'baseUrl' => $this->baseUrl,
                'item' => $result,
            ]);

        $result['_links'] = Factory\HAL\Product::getLinks([
                'origin' => $this->origin,
                'baseUrl' => $this->baseUrl,
                'item' => $result,
            ]);

        $this->hal($result);
    }

    public function getTags($req, $next) {

        $offset = QueryParams::getOffset();
        $limit = QueryParams::getLimit();

        $qb = \Model\Tags\Tags::find();
        $qbCnt = clone $qb;
        $qb = $qb->limit([ $offset, $limit ]);

        $tags = $qb->getAll();
        $total = $qbCnt->count();

        $result = Factory\HAL\Tags::get([
            'items' => $tags,
            'total' => $total,
            'offset' => $offset,
            'limit' => $limit,
        ]);

        $this->hal($result);
    }

    public function getTag($req, $next) {

        $tag = \Model\Tags\Tags::find(['id' => $req['id']])
            ->get();

        $result = Factory\HAL\Tag::get([
            'item' => $tag,
        ]);

        $this->hal($result);
    }
}
