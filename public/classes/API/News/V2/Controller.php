<?php

namespace API\News\V2;

use Core\Response;
use Core\Router\Router;

use Back\Pages\Models\PagesModel as Page;

use Websm\Framework\Di;

class Controller extends Response {

    const LIMIT = 30;

    const FORCED_SCHEME = 'http';

    const ORDER_FIELDS = [ 
        'title' => 'title',
        'creationDate' => 'date',
    ];

    const ORDER_TYPES = [ 
        'asc' => 'ASC',
        'desc' => 'DESC',
    ];

    private $origin;
    private $baseUrl;

    public function __construct() {

        //$this->origin = self::FORCED_SCHEME . '://'. $_SERVER['SERVER_NAME'];
        $this->origin = '';
        $this->baseUrl = $this->origin . $_SERVER['REQUEST_URI'];
    }

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/news', [$this, 'getNews']);
        $group->addGet('/news/:id', [$this, 'getNewsItem']);
        $group->addGet('/doc/rels/:rel', [$this, 'getRelDoc']);

        return $group;

    }

    public function getNews($req, $next) {

        $offset = isset($_GET['offset']) ? (Integer)$_GET['offset'] : 0;
        $limit = isset($_GET['limit']) ? (Integer)$_GET['limit'] : self::LIMIT;
        $basePath = '/api/news/v2';

        $orderRaw = isset($_GET['order']) ? $_GET['order'] : null;
        $order = [];

        if (!is_array($orderRaw)) {
            $orderRaw = [ 'creationDate' => 'desc' ];
        }

        foreach($orderRaw as $key => $value) {

            if (array_key_exists($key, self::ORDER_FIELDS)) {

                if (array_key_exists($value, self::ORDER_TYPES))
                    $order[self::ORDER_FIELDS[$key]] = self::ORDER_TYPES[$value];
                else
                    $order[self::ORDER_FIELDS[$key]] = 'ASC';

            }
        }

        try {

            $newsIndex = Page::byTags(['Новости'])
                ->get();

            $qb = Page::find(['cid' => $newsIndex->id, 'visible' => true]);
            $qbCnt = clone $qb;

            $orderFmt = [];
            foreach($order as $key => $value)
                $orderFmt[] = '`' . $key . '` ' . $value; 

            $news = $qb->order($orderFmt)
                ->limit([ $offset, $limit ])
                ->getAll();

            if ($newsIndex->isNew())
                throw new Exceptions\HTTP('index page not found', 404);

            $result = [];

            foreach($news as $page) {

                $picSize = 500;
                $picture = $page->getPicture($picSize . 'x' .$picSize);

                $result[] = [
                    'id' => (String)$page->id,
                    'title' => (String)$page->title,
                    'announce' => (String)$page->announce,
                    'picture' => $picture,
                    'creationDate' => $page->date,
                    /* 'pageRef' => $this->origin . '/' . $page->chpu, */
                    'pageRef' => self::FORCED_SCHEME .':///' . $_SERVER['SERVER_NAME'] . '/' . $page->chpu,
                ];

            }

            header('Content-Type: application/hal+json');

            $total = (Integer)$qbCnt->count();

            $this->json([
                '_embedded' => Factory::getNewsHalEmbedded([
                    'items' => $result,
                    'baseUrl' => $this->baseUrl,
                    'origin' => $this->origin,
                ]),
                '_links' => Factory::getNewsHalLinks([
                    'baseUrl' => $this->baseUrl,
                    'origin' => $this->origin,
                    'total' => $total,
                    'offset' => $offset,
                    'limit' => $limit,
                ]),
                'offset' => $offset,
                'limit' => $limit,
                'size' => (Integer)count($news),
                'total' => $total,
            ]);

        } catch(Exceptions\HTTP $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ],
            ]);

        }

    }

    public function getNewsItem($req, $next) {

        $origin = self::FORCED_SCHEME . '://'. $_SERVER['SERVER_NAME'];
        $basePath = '/api/news/v2';
        $baseUrl = $origin . $basePath;

        try {

            $pageId = $req['id'];

            $newsIndex = Page::byTags(['Новости'])
                ->get();

            if ($newsIndex->isNew())
                throw new Exceptions\HTTP('index page not found', 404);

            $page = Page::find([
                'id' => $pageId,
                'cid' => $newsIndex->id,
                'visible' => true
            ])
                ->get();

            if ($page->isNew())
                throw new Exceptions\HTTP('page not found', 404);

            $picSize = 500;
            $picture = $page->getPicture($picSize . 'x' .$picSize);

            header('Content-Type: application/hal+json');

            $result = [
                'id' => (String)$page->id,
                'title' => (String)$page->title,
                'pageRef' => self::FORCED_SCHEME .':///' . $_SERVER['SERVER_NAME'] . '/' . $page->chpu,
                'announce' => (String)$page->announce,
                'picture' => $picture,
                'creationDate' => $page->date,
                'text' => $page->text,
            ];

            $result['_links'] = Factory::getNewsItemHalLinks([
                'item' => $result,
                'origin' => $origin,
                'baseUrl' => $baseUrl,
            ]);
            $result['_embedded'] = Factory::getNewsItemHalEmbedded([
                'item' => $result,
                'origin' => $origin,
                'baseUrl' => $baseUrl,
            ]);

            $this->json($result);

        } catch(Exceptions\HTTP $e) {

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
