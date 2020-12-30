<?php

namespace Front\News;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Websm\Framework\Di\Container as Di;

use Components\Pager\Widget as Pager;

use Model\Page;

class Controller extends Response
{
    private const PAGE_ID = 'db9de48a6b97da4e1cb488008e6efdb2';
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
        $pageNum = $req->query['page'] ?? 1;

        $newsQb = Page::find([ 'cid' => self::PAGE_ID ])
            ->andWhere([ 'visible' => true ])
            ->order('`sort`');

        $pager = new Pager(
            $newsQb,
            self::ITEMS_ON_PAGE,
            $pageNum
        );

        $news = $pager->getItems();

        $pagerHtml = $pager->getHtml();

        $data = [
            'news' => $news,
            'pager' => $pagerHtml,
        ];

        $html = $this->render(__DIR__.'/temp/news.tpl', $data);

        $this->layout
            ->setSrc('news')
            ->setContent($html);
    }

    public function getNew($req, $next)
    {
        $route = Router::instance();
        $chpu = $route->getAbsolutePath();

        $page = Page::find(['chpu' => $chpu])
            ->get();

        $data = [
            'page' => $page
        ];
        
        \Components\Seo\Seo::setContent($page->title, $page->keywords, $page->announce);

        $html = $this->render(__DIR__ . '/temp/news.tpl', $data);

        $this->layout
            ->setSrc('pages')
            ->setContent($html);   
    }
}
