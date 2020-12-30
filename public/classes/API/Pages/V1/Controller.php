<?php

namespace API\Pages\V1;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;

use Model\Page;
use Websm\Framework\Exceptions\HTTP as HTTPException;

use Websm\Framework\Di;

class Controller extends Response {

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', function($req, $next) {

            $routes = [
                'self' => Router::byName('api:pages:v1')
                    ->getAbsolutePath(),
                'pages' => Router::byName('api:pages:v1:pages')
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
                    'pages' => [
                        'href' => $origin . $routes['pages'],
                    ],
                ],
            ]);
        })->setName('api:pages:v1');

        $group->addGet('/pages', [$this, 'getPages'])
            ->setName('api:pages:v1:pages');
        $group->addGet('/pages/:id', [$this, 'getPage'])
            ->setName('api:pages:v1:page');
        $group->addGet('/pages/:id/subpages', [$this, 'getSubpages'])
            ->setName('api:pages:v1:subpages');

        $group->addGet('/doc/rels/:rel', [$this, 'getRelDoc']);

        return $group;
    }

    public function getPages($req, $next) {

        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $order = Factory\QueryParams::getOrder();
        $tags = Factory\QueryParams::getTags();
        $query = &$_GET['query'];

        try {

            $qb = \Model\Page::find();
            $qb = Factory\Filters\QB::filterQuery($qb, $query);
            $qb = Factory\Filters\QB::filterTags($qb, $tags);
            $qb = $qb->andWhere(['visible' => true]);
            $qbCnt = clone $qb;

            $pages = $qb->order($order)
                ->limit([ $offset, $limit ])
                ->getAll();

            $total = (Integer)$qbCnt->count();

            $response = Factory\HAL\Pages::get([
                'items' => $pages,
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

    public function getPage($req, $next) {

        try {

            $page = \Model\Page::find(['id' => $req['id']])
                ->get();

            if ($page->isNew())
                throw new HTTPException('page not found', 404);

            $response = Factory\HAL\Page::get([
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

    public function getSubpages($req, $next) {

        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $order = Factory\QueryParams::getOrder();
        $tags = Factory\QueryParams::getTags();
        $query = &$_GET['query'];

        try {

            $qb = \Model\Page::find();
            $qb = Factory\Filters\QB::filterQuery($qb, $query);
            $qb = Factory\Filters\QB::filterTags($qb, $tags);
            $qb = $qb->andWhere(['visible' => true])
                ->andWhere(['cid' => $req['id']]);
            $qbCnt = clone $qb;

            $pages = $qb->order($order)
                ->limit([ $offset, $limit ])
                ->getAll();

            $total = (Integer)$qbCnt->count();

            $response = Factory\HAL\Subpages::get([
                'items' => $pages,
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
}
