<?php

namespace API\News\V1; 

use Core\Router\Router;
use Core\Response;
use \Datetime;

use Front\Pages\Models\PagesModel;
use API\News\V1\models\Parameters;

use Websm\Framework\Types\ArrayHelper;
use Websm\Framework\Pager\Pager;

class News {

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', function () {

            $res = new Response;
            $html = $res->render(__DIR__ . '/news.html');
            $res->send($html);

        });

        $group->addGet('/news', [$this, 'getNews'])
            ->setName('News.api.v1.getNews');

        $group->addGet('/news/:id', [$this, 'getNew'])
            ->setName('News.api.v1.getNew');

        return $group;

    }

    public function getNews() {

        $res = new Response;

        $params = new Parameters;
        $params->bind($_GET);

        $validParams = $params->getValidateParams();

        if (!isset($validParams['Errors'])) {

            $route = Router::byName('News.api.v1.getNews');

            $ref = $route->getAbsolutePath();
            $count = (int)PagesModel::getCountNewsFilter($validParams);
            $pager = new Pager((int)$count, $validParams['page'], $validParams['limit']);

            $links = $this->getLinks($ref, $pager, $validParams);
            $fields = $this->generateNewsArray($validParams); 

            $body['total'] = $count; 
            $body['limit'] = $validParams['limit'];
            $body['count'] = count($fields);
            $body['page'] = $validParams['page'];
            $body['links'] = $links;
            $body['fields'] = $fields;

            $res->json($body);

        } else {

            $res->code(422);
            $res->json($validParams['Errors']);
            $res->send();

        }
    }

    public function getNew($req) {

        $res = new Response;

        $new = PagesModel::getPageId($req['id']);

        if ($new) {

            $body = [
                'id' => $new->id,
                'title' => $new->title,
                'announce' => $new->announce,
                'text' => $new->text,
                'date' => date("Y.m.d", strtotime($new->date)),
                'picture' => $new->getPicture('600x400'),
                'link' => $this->getAbsoluteLink($new->chpu)
            ];

            $res->json($body);

        } else {

            $res->code(404);
            $res->send();

        }

    }

    private function getLinks($ref, $pager, $params) {

        $link = $this->getAbsoluteLink($ref);

        $links = [
            'current' => $link.'?page='.$pager->getCurrentPage(),
            'start' => $link.'?page=1',
            'finish' => $link.'?page='.$pager->getCountPages(),
            'next' => $link.'?page='.$pager->getNextPage(),
            'prev' => $link.'?page='.$pager->getPervPage(),
        ];

        foreach ($links as $key => $value) {

            $links[$key] = $links[$key] . '&limit=' . $params['limit'];
            $links[$key] = $links[$key] . '&sort=' . (string)SortEnum::indexOf(($params['sort']));
            $links[$key] = $links[$key] . '&order=' . $params['order']; 
            $links[$key] = $links[$key] . '&fromDate=' . date('d.m.Y', strtotime($params['fromDate'])); 
            $links[$key] = $links[$key] . '&toDate=' . date('d.m.Y', strtotime($params['toDate'])); 

        };

        return $links;

    }

    private function generateNewsArray($validParams) {

        $items = PagesModel::getAllNewsFilterDate(...array_values($validParams));   

        $res = array_map(function ($item) {

            $picture = ($item->picture) 
                ? $this->getAbsoluteLink($item->getPicture('600x400'))
                : $item->getPicture('600x400');

            $ret = (Object)[];
            $ret->id = $item->id;
            $ret->title = $item->title;
            $ret->picture = $picture;
            $ret->announce = $item->announce;
            $ret->date = date("d.m.Y", strtotime($item->date));
            $ret->sort = $item->sort;
            $ret->link = $this->getAbsoluteLink($item->chpu);

            return $ret;

        }, $items);

        return $res;

    }

    private function getAbsoluteLink($link) {

        if (preg_match('/^http*/i', $link) || $link == null) {

            return $link;

        } else {

            /* $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://'; */

            $protocol = 'https://';
            $host = $_SERVER['SERVER_NAME'];

            $link = $protocol . $host . '/' . $link;

            return $link;

        }

    }

}
