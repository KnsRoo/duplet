<?php

namespace Front\Main;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Websm\Framework\Di\Container as Di;

use Model\Catalog\Product;

class Controller extends Response
{
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

        $group->addGet('/', [$this, 'getContent']);

        return $group;
    }

    public function getContent()
    {
        //$filer = new FillerDb();
        //$filer->fillTableProduct();

        $new = Product::byTags(['Новинки'])->getAll();

        $data = [ 'new' => $new];

        $html = $this->render(__DIR__ . '/temp/default.tpl', $data);

        $this->layout
            ->setSrc('index')
            ->setContent($html);
    }
}
