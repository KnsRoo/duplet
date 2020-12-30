<?php

namespace API\News\V3;

use Core\Response;
use Core\Router\Router;

use Back\Pages\Models\PagesModel as Page;
use Websm\Framework\Exceptions\HHTP as HTTPException;

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
                'news' => Router::byName('api:news:v3:news')
                    ->getAbsolutePath(),
            ];

            $this->hal([
                '_links' => [
                    'self' => [
                        'href' => 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'],
                    ],
                    'items' => [
                        'href' => 'https://' . $_SERVER['SERVER_NAME'] . $routes['news'],
                    ],
                ],
            ]);
        })->setName('api:news:v3');

        $group->addGet('/news', [$this, 'getNews'])
            ->setName('api:news:v3:news');

        $group->addGet('/news/:id', [$this, 'getNewsItem'])
            ->setName('api:news:v3:news-item');

        $group->addGet('/doc/rels/:rel', [$this, 'getRelDoc']);

        return $group;

    }

    public function getNews($req, $next) {

        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $order = Factory\QueryParams::getOrder();

        try {

            $newsIndex = Page::byTags(['Новости'])
                ->get();

            if ($newsIndex->isNew())
                throw new HTTPException('index page not found', 404);

            $qb = Page::find([
                'cid' => $newsIndex->id,
                'visible' => true
            ]);

            $qbCnt = clone $qb;

            $news = $qb->order($order)
                ->limit([ $offset, $limit ])
                ->getAll();

            $total = (Integer)$qbCnt->count();

            $response = Factory\HAL\News::get([
                'items' => $news,
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

    public function getNewsItem($req, $next) {

        $pageId = $req['id'];

        try {

            $newsIndex = Page::byTags(['Новости'])
                ->get();

            if ($newsIndex->isNew())
                throw new HTTPException('index page not found', 404);

            $page = Page::find([
                'id' => $pageId,
                'cid' => $newsIndex->id,
                'visible' => true
            ])
                ->get();

            if ($page->isNew())
                throw new HTTPException('page not found', 404);

            $result = Factory\HAL\NewsItem::get(['item' => $page]);
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
