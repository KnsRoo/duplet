<?php

namespace Front\News;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Websm\Framework\Di\Container as Di;

use Components\Pager\Widget as Pager;

use Model\Page;

class Controller extends Response
{
    private const PAGE_ID = 'e813e4e7e7d947c5b0c14b75d82fa6a4';
    private const ITEMS_ON_PAGE = 2;

    private $di;
    private $layout;

    public function __construct()
    {
        $this->di = Di::instance();
        $this->layout = $this->di->get('layout');
    }

    public function getRoutes()
    {
        $group = Router::group();

        $group->addGet('/', [ $this, 'getNews' ])
            ->setName('news:news');

        $group->addGet('/:newChpu', [ $this, 'getNew' ])
            ->setName('news:new');

        return $group;
    }

    public function getNews($req)
    {

        $page = Page::find(['id' => self::PAGE_ID])
            ->get();

        \Components\Seo\Seo::setContent($page->title, $page->keywords, $page->announce);

        $html = $this->render(__DIR__.'/temp/news.tpl', []);

        $this->layout
            ->setSrc('news')
            ->setContent($html);
    }

    public function getNew($req, $next)
    {
        $route = Router::instance();
        $chpu = $route->getAbsolutePath();

        $newsQb = Page::find([ 'cid' => self::PAGE_ID ])
            ->andWhere([ 'visible' => true ])
            ->order('`date` DESC')
            ->getAll();

        $page = Page::find(['chpu' => $chpu])
            ->get();

        $key = array_search($page, $newsQb);

        $next = $newsQb[$key+1];

        $data = [
            'page' => $page,
            'next' => $next ? $next : false
        ];
        
        \Components\Seo\Seo::setContent($page->title, $page->keywords, $page->announce);

        $html = $this->render(__DIR__ . '/temp/new.tpl', $data);

        $this->layout
            ->setSrc('news')
            ->setContent($html);   
    }
}
