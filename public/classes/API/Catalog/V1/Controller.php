<?php

namespace API\Catalog\V1;

use Core\Response;
use Core\Router\Router;

use Back\Catalog\Models\Groups as Group;
use Back\Catalog\Models\Products as Product;

use Websm\Framework\Di;

class Controller extends Response {

    const FORCED_SCHEME = 'https';

    const BASE_PATH = '/api/catalog/v1';

    private $queryParams = [];

    public function __construct() {

        //$this->origin = self::FORCED_SCHEME . '://'. $_SERVER['SERVER_NAME'];
        $this->origin = '';
        $this->baseUrl = $this->origin . self::BASE_PATH;

    }

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', function($req, $next) {

            header('Content-Type: application/hal+json');

            $this->json([
                '_links' => [
                    'self' => [
                        'href' => $this->origin . $_SERVER['REQUEST_URI'],
                    ],
                    'groups' => [
                        'href' => $this->baseUrl . '/groups',
                    ],
                    'root-group' => [
                        'href' => $this->baseUrl . '/groups?tags[]=root',
                    ],
                    'search' => [
                        'href' => $this->baseUrl . '/search',
                    ],
                ],
            ]);
        });

        $group->addGet('/groups', [$this, 'getGroups']);
        $group->addGet('/groups/:id', [$this, 'getGroup']);
        $group->addGet('/groups/:id/subgroups', [$this, 'getSubgroups']);
        $group->addGet('/groups/:gid/products', [$this, 'getProducts']);
        $group->addGet('/groups/:gid/products/:id', [$this, 'getProduct']);
        $group->addGet('/search', [$this, 'getSearchResult']);
        $group->addGet('/doc/rels/:rel', [$this, 'getRelDoc']);

        return $group;

    }

    public function getGroups($req, $next) {

        $offset = QueryParams::getOffset();
        $limit = QueryParams::getLimit();
        $tags = QueryParams::getTags();
        $order = QueryParams::getOrderGroups();

        $qb = Group::find(['visible' => true]);

        $qb = Factory\Filters\QB::filterTags($qb, $tags);
        $qbCnt = clone $qb;

        $groups = $qb->order($order)
            ->limit([ $offset, $limit ])
            ->getAll();

        $result = [];

        foreach($groups as $group) {

            $picSize = 500;
            $picture = $group->getPicture($picSize . 'x' .$picSize);

            $result[] = [
                'id' => (String)$group->id,
                'title' => (String)$group->title,
                'code' => (String)$group->code,
                'preview' => (String)$group->preview,
                'picture' => $picture,
                'pageRef' => $this->origin . '/katalog' . $group->chpu,
            ];

        }

        header('Content-Type: application/hal+json');

        $total = (Integer)$qbCnt->count();
        $this->json([
            '_embedded' => Factory\HAL\Groups::getEmbedded([
                'origin' => $this->origin,
                'baseUrl' => $this->baseUrl,
                'items' => $result,
            ]),
            '_links' => Factory\HAL\Groups::getLinks([
                'origin' => $this->origin,
                'baseUrl' => $this->baseUrl,
                'total' => $total,
                'offset' => $offset,
                'limit' => $limit,
            ]),
            'offset' => $offset,
            'limit' => $limit,
            'size' => (Integer)count($groups),
            'total' => $total,
        ]);

    }

    public function getGroup($req, $next) {

        $id = $req['id'];

        $group = Group::find([ 'id' => $id, 'visible' => true, ])
            ->get();

        try {

            if ($group->isNew()) throw new Exceptions\HTTP('group not found', 404);

        } catch(Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ],
            ]);

        }

        $children = Group::find([ 'cid' => $id, 'visible' => true, ])
            ->getAll();

        $picSize = 1000;
        $picture = $group->getPicture($picSize . 'x' .$picSize);

        header('Content-Type: application/hal+json');

        $result = [
            'id' => (String)$group->id,
            'title' => (String)$group->title,
            'code' => (String)$group->code,
            'preview' => (String)$group->preview,
            'picture' => $picture,
            'pageRef' => $this->origin . '/Katalog/' . $group->chpu,
        ];

        $result['_links'] = Factory\HAL\Group::getLinks([
            'baseUrl' => $this->baseUrl,
            'item' => $result,
        ]);

        $result['_embedded'] = Factory\HAL\Group::getEmbedded([
            'origin' => $this->origin,
            'baseUrl' => $this->baseUrl,
            'item' => $result,
        ]);

        $this->json($result);

    }

    public function getSubgroups($req, $next) {

        $groupId = $req['id'];
        $offset = QueryParams::getOffset();
        $limit = QueryParams::getLimit();
        $order = QueryParams::getOrderGroups();
        $tags = QueryParams::getTags();

        $qb = Group::find([ 'visible' => true, 'cid' => $groupId ]);

        $qb = Factory\Filters\QB::filterTags($qb, $tags);

        $qbCnt = clone $qb;

        $groups = $qb->order($order)
            ->limit([ $offset, $limit ])
            ->getAll();

        $result = [];

        foreach($groups as $group) {

            $picSize = 500;
            $picture = $group->getPicture($picSize . 'x' .$picSize);

            $result[] = [
                'id' => (String)$group->id,
                'title' => (String)$group->title,
                'code' => (String)$group->code,
                'preview' => (String)$group->preview,
                'picture' => $picture,
                'pageRef' => $this->origin . '/katalog' . $group->chpu,
            ];

        }

        header('Content-Type: application/hal+json');

        $total = (Integer)$qbCnt->count();
        $this->json([
            '_embedded' => Factory\HAL\Subgroups::getEmbedded([
                'groupId' => $groupId,
                'origin' => $this->origin,
                'baseUrl' => $this->baseUrl,
                'items' => $result,
            ]),
            '_links' => Factory\HAL\Subgroups::getLinks([
                'groupId' => $groupId,
                'origin' => $this->origin,
                'baseUrl' => $this->baseUrl,
                'total' => $total,
                'offset' => $offset,
                'limit' => $limit,
            ]),
            'offset' => $offset,
            'limit' => $limit,
            'size' => (Integer)count($groups),
            'total' => $total,
        ]);

    }

    public function getProducts($req, $next) {

        $offset = QueryParams::getOffset();
        $limit = QueryParams::getLimit();
        $order = QueryParams::getOrderProducts();
        $tags = QueryParams::getTags();
        $props = QueryParams::getProps();

        $groupId = $req['gid'];

        $group = Group::find([ 'id' => $groupId, 'visible' => true ])
            ->get();

        try {

            if ($group->isNew())
                throw new Exceptions\HTTP('group not found', '404');

        } catch(Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ],
            ]);
        }

        $qb = Product::find(['cid' => $groupId]);

        $qb = Factory\Filters\QB::filterTags($qb, $tags);
        $qb = Factory\Filters\QB::filterProps($qb, $props);

        $qbCnt = clone $qb;

        $products = $qb->order($order)
            ->limit([ $offset, $limit ])
            ->getAll();

        $result = [];

        foreach($products as $product) {

            $picSize = 500;
            $picture = $product->getPicture($picSize . 'x' .$picSize);
            $tags = explode(':', trim($product->tags, ':'));

            $result[] = [
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
                'tags' => $tags,
                'props' => json_decode($product->props),
            ];
        }

        header('Content-Type: application/hal+json');

        $total = (Integer)$qbCnt->count();
        $this->json([
            '_embedded' => Factory\HAL\Products::getEmbedded([
                'origin' => $this->origin,
                'baseUrl' => $this->baseUrl,
                'items' => $result,
                'groupId' => $group->id,
            ]),
            '_links' => Factory\HAL\Products::getLinks([
                'origin' => $this->origin,
                'baseUrl' => $this->baseUrl,
                'total' => $total,
                'offset' => $offset,
                'limit' => $limit,
                'groupId' => $group->id,
            ]),
            'offset' => $offset,
            'limit' => $limit,
            'size' => (Integer)count($products),
            'total' => $total,
        ]);

    }

    public function getProduct($req, $next) {

        $gid = $req['gid'];
        $id = $req['id'];

        $product = Product::find([ 'cid' => $gid, 'id' => $id, 'visible' => true, ])
            ->get();

        try {

            if ($product->isNew()) throw new Exceptions\HTTP('product not found', 404);

        } catch(Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ],
            ]);
        }

        $picSize = 1000;
        $picture = $product->getPicture($picSize . 'x' .$picSize);

        header('Content-Type: application/hal+json');

        $tags = explode(':', trim($product->tags, ':'));

        $result = [
            'id' => (String)$product->id,
            'code' => (String)$product->code,
            'title' => (String)$product->title,
            'price' => (float)$product->price,
            'pageRef' => $this->origin . '/Katalog/' . $product->chpu,
            'preview' => (String)$product->preview,
            'about' => (String)$product->about,
            'picture' => $picture,
            'creationDate' => $product->date,
            'sort' => $product->sort,
            'tags' => $tags,
            'props' => json_decode($product->props),
        ];

        $result['_links'] = Factory\HAL\Product::getLinks([
            'baseUrl' => $this->baseUrl,
            'item' => $result,
            'groupId' => $product->cid,
        ]);

        $result['_embedded'] = Factory\HAL\Product::getEmbedded([
            'origin' => $this->origin,
            'baseUrl' => $this->baseUrl,
            'item' => $result,
        ]);

        $this->json($result);

    }

    public function getSearchResult($req, $next) {

        $offset = QueryParams::getOffset();
        $limit = QueryParams::getLimit();
        $order = QueryParams::getOrderProducts();
        $tags = QueryParams::getTags();
        $props = QueryParams::getProps();

        $qb = Product::find([ 'visible' => true ]);

        $qb = Factory\Filters\QB::filterTags($qb, $tags);
        $qb = Factory\Filters\QB::filterProps($qb, $props);

        $qbCnt = clone $qb;

        $products = $qb->order($order)
            ->limit([ $offset, $limit ])
            ->getAll();

        $result = [];

        $cids = [];

        foreach($products as $product) {

            $picSize = 500;
            $picture = $product->getPicture($picSize . 'x' .$picSize);

            $result[] = [
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

            $cids[$product->id] = $product->cid;
        }

        header('Content-Type: application/hal+json');

        $total = (Integer)$qbCnt->count();
        $this->json([
            '_embedded' => Factory\HAL\Search::getEmbedded([
                'origin' => $this->origin,
                'baseUrl' => $this->baseUrl,
                'items' => $result,
                'cids' => $cids,
            ]),
            '_links' => Factory\HAL\Search::getLinks([
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

    public function getRelDoc($req, $next) {

        $validRels = [ 'groups', 'group', 'subgroups', 'products', 'product', 'search' ];
        $validAccepts = [ 'text/html', 'text/yaml' ];

        $headers = apache_request_headers();
        $accepts = &$headers['Accept'];
        $accepts = (String)$accepts;
        $accepts = explode(',', $accepts);
        $accept = 'text/yaml';

        foreach($accepts as $acceptItem)
            if (in_array($acceptItem, $validAccepts)) {
                $accept = $acceptItem;
                break;
            }

        $rel = $req['rel'];
        if(!in_array($rel, $validRels)) {
            $this->code(404);
            die();
        }

        switch($accept) {
            case 'text/html':
                header('Content-Type: text/html');
                die(file_get_contents(__DIR__ . "/doc/${rel}.html"));
                break;
            case 'text/yaml':
                header('Content-Type: text/yaml');
                die(file_get_contents(__DIR__ . "/doc/${rel}.yml"));
                break;
        }
    }
}
