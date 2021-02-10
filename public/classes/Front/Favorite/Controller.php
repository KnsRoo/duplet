<?php

namespace Front\Favorite;

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
            ->setName('favorites:default');

        return $group;
    }

    public function getDefault($req)
    {
        $html = $this->render(self::PATH . 'favorite.tpl');

        $this->layout
            ->setSrc('favorite')
            ->setContent($html);
    }
}
