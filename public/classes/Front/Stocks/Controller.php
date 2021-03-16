<?php

namespace Front\Stocks;

use Model\Page;
use Websm\Framework\Di\Container as Di;
use Websm\Framework\Response;
use Websm\Framework\Router\Router;

class Controller extends Response
{
    const PATH = __DIR__ . '/temp/';
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

        $group->addGet('/', [$this, 'getDefault'])
            ->setName('stocks:default');

        return $group;
    }

    public function getDefault($req)
    {
        $page = Page::find(['chpu' => '/Stocks'])
            ->get();

        \Components\Seo\Seo::setContent($page->title, $page->keywords, $page->announce);

        $data = [
            'link' => Router::byName('api:pages:v1:subpages')->getURL(['id' => $page->id])
        ];

        $html = $this->render(self::PATH . 'stocks.tpl', $data);

        $this->layout
            ->setSrc('stocks')
            ->setContent($html);
    }
}
