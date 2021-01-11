<?php

namespace API\News\V4;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;

use Model\Page;
use Websm\Framework\Exceptions\HTTP as HTTPException;

use Websm\Framework\Di;

class Controller extends Response {

    public function hal($data) {

        header('Content-Type: application/hal+json');
        die(json_encode($data));
    }

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', function($req, $next) {

            $routes = [
                'lines' => Router::byName('api:news:v4:lines')
                    ->getAbsolutePath(),
            ];

            $protocol = 'http';

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                $protocol = 'https';

            $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

            $apiBaseUrl = $origin . Router::byName('api:news:v4')
                ->getAbsolutePath();

            $this->hal([
                '_links' => [
                    'self' => [
                        'href' => $origin . $_SERVER['REQUEST_URI'],
                    ],
                    'curies' => [
                        [
                            'name' => 'doc',
                            'href' => $apiBaseUrl . '/doc/rels/{rel}',
                            'templated' => true,
                        ],
                    ],
                    'doc:lines' => [
                        'href' => $origin . $routes['lines'],
                    ],
                ],
            ]);
        })->setName('api:news:v4');

        $group->addGet('/lines', [$this, 'getLines'])
            ->setName('api:news:v4:lines');

        $group->addGet('/lines/:id', [$this, 'getLine'])
            ->setName('api:news:v4:line');

        $group->addGet('/lines/:lid/items', [$this, 'getLineItems'])
            ->setName('api:news:v4:lineitems');

        $group->addGet('/lines/:lid/items/:id', [$this, 'getLineItem'])
            ->setName('api:news:v4:lineitem');

        $group->addGet('/doc/rels/:rel', [$this, 'getRelDoc']);

        return $group;
    }

    public function getLines($req, $next) {

        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $order = Factory\QueryParams::getOrderLines();
        $tags = Factory\QueryParams::getTags();

        try {

            $qb = \Model\Page::find(['cid' => 'e813e4e7e7d947c5b0c14b75d82fa6a4']);
            $qb = Factory\Filters\QB::filterTags($qb, $tags);
            $qb = $qb->andWhere(['visible' => true]);
            $qbCnt = clone $qb;

            $lines = $qb->order($order)
                ->limit([ $offset, $limit ])
                ->getAll();

            $total = (Integer)$qbCnt->count();

            $response = Factory\HAL\Lines::get([
                'items' => $lines,
                'total' => $total,
                'offset' => $offset,
                'limit' => $limit,
            ]);

            $this->hal($response);

        } catch(HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ],
            ]);
        }
    }

    public function getLine($req, $next) {

        try {

            $page = \Model\Page::find(['id' => $req['id']])
                ->get();

            if ($page->isNew())
                throw new HTTPException('page not found', 404);

            $response = Factory\HAL\Line::get([
                'item' => $page,
            ]);

            $this->hal($response);

        } catch(HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ],
            ]);
        }
    }

    public function getLineItems($req, $next) {

        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $order = Factory\QueryParams::getOrderLineItems();
        $tags = Factory\QueryParams::getTags();

        try {

            $qb = \Model\Page::find(['cid' => $req['lid']]);
            $qb = Factory\Filters\QB::filterTags($qb, $tags);
            $qb = $qb->andWhere(['visible' => true]);
            $qbCnt = clone $qb;

            $lines = $qb->order($order)
                ->limit([ $offset, $limit ])
                ->getAll();

            $total = (Integer)$qbCnt->count();

            $response = Factory\HAL\LineItems::get([
                'items' => $lines,
                'total' => $total,
                'offset' => $offset,
                'limit' => $limit,
            ]);

            $this->hal($response);

        } catch(HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ],
            ]);
        }
    }

    public function getLineItem($req, $next) {

        try {

            $linePage = Page::find([ 'id' => $req['lid'], 'visible' => true ])
                ->get();

            if ($linePage->isNew())
                throw new HTTPException('index page not found', 404);

            $page = Page::find([
                'id' => $req['id'],
                'cid' => $req['lid'],
                'visible' => true
            ])
                ->get();

            if ($page->isNew())
                throw new HTTPException('page not found', 404);

            $result = Factory\HAL\LineItem::get(['item' => $page]);
            $this->hal($result);

        } catch(HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ],
            ]);

        }
    }

    public function getRelDoc($req, $next) {

        $rel = $req['rel'];

        switch($rel) {
            case 'news':
                header('Content-Type: text/yaml');
                die($this->render(__DIR__ . '/doc/news.yml'));
                break;
            case 'news-item':
                header('Content-Type: text/yaml');
                die($this->render(__DIR__ . '/doc/news-item.yml'));
                break;
            default:
                $this->code(404);
                die();
        }
    }
}
