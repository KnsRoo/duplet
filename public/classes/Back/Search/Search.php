<?php

namespace Back\Search;

use Back\Journal\JournalModel;

use Websm\Framework\Db\Qb;
use Websm\Framework\Response;
use Websm\Framework\Notify;
use Websm\Framework\Router\Router;
use Core\ModuleInterface;

use Websm\Framework\Di;
use Websm\Framework\Pager\Pager;
use Websm\Framework\Search\TypesEnum;

class Search extends Response implements ModuleInterface  {

    const ITEMS_ON_PAGE = 21;
    const TEMPLATES = __DIR__ . '/temp/';

    private $di;

    public $permitions = [];

    protected $url = 'search';
    protected $title = 'Поиск';
    protected $resultForm;

    private $message = [
        'status' => 'ok',
        'notify' => [],
        'content' => '',
    ];

    public function __construct(&$props = []) {}

    public function setSettings(Array &$props = []) { }

    public function getSettings() { return $this->permitions; }

    public function getRoutes() {

        $group = Router::group();

        $group->mount('/api', $this->getRoutesAPI());

        $group->addGet('/', [$this, 'parseRequest'], ['end' => false]);

        $group->addGet('/get-results', [$this, 'search'])
            ->setName('Search.search');

        return $group;

    }

    private function getRoutesAPI() {

        $group = Router::group();

        $group->addGet('/get-results', [$this, 'getResultsAPI']);

        return $group;

    }

    public function init($req, $next) {

        $this->css = [
            'css/search.css',
            'css/catalog.css',
        ];

        $this->js = [
            'js/search.js',
            'js/search-view.js',
        ];

        $route = Router::instance();

        $route->mount('/', $this->getRoutes());

        $next();

    }

    public function parseRequest($req, $next) {

        $page = &$_REQUEST['page'];
        $this->page = $page ?: 1;
        $next();

    }

    public function getResultsAPI() {

        $di = $this->di;
        $di = Di\Container::instance();
        $search = $di->get('search');
        $res = new Response;

        $query = &$_GET['query'];
        $query = trim($query);

        if (mb_strlen($query, 'UTF-8') < 4) {

            $res->code(403);
            $res->send('Query is small');

        }

        $search->setQuery($query);
        $search->setReturnType(new TypesEnum('JSON'));
        $result = $search->getResult();

        if (!count($result)) {

            $res->code(404);
            $res->send();

        }

        $res->json($result);

    }

    public function search($req) {

        $di = $this->di;
        $di = Di\Container::instance();
        $query = clone $req->query;
        $route = Router::byName('Search.search');
        $action = $route->getAbsolutePath();

        $name = &$_GET['name'];
        $name = trim($name);

        $findQuery = &$_GET['query'];
        $findQuery = trim($findQuery);
        $search = $di->get('search');

        if (mb_strlen($findQuery, 'UTF-8') < 3) {

            Notify::push(
                'Длина запроса для поиска должна быть 3 или более символов.',
                'err'
            );

            $this->back();

        }

        $search->setQuery($findQuery);

        $count = $search->getCount($name);
        $result = $search->getResult($name);

        $pager = new Pager(count($result), $this->page, self::ITEMS_ON_PAGE);
        $result = $pager->chunkItems($result);

        $data = [
            'pager' => $pager,
            'action' => $action,
            'query' => clone $query,
        ];

        $pager = $this->render(self::TEMPLATES . 'pager.tpl', $data);

        $data = [];
        $data['result'] = $result;
        $data['pager'] = $pager;

        $this->resultForm = $this->render(self::TEMPLATES . 'result.tpl', $data);

    }

    public function getContent() {

        return $this->render(self::TEMPLATES . 'default.tpl');

    }

    public function getSettingsContent($name = '', Array $permitions) {

        return $this->render(self::TEMPLATES . 'settings.tpl', $permitions);

    }

}
