<?php

namespace Front\lk;

use Model\Catalog\Group;
use Model\Catalog\Product;
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
            ->setName('lk:default');

        return $group;
    }

    public function getDefault($req)
    {

        \Components\Seo\Seo::setContent('Личный кабинет - Оружейный магазин "Дуплет"');

        $html = $this->render(self::PATH . 'lk.tpl');

        $this->layout
            ->setSrc('lk')
            ->setContent($html);
    }
}
