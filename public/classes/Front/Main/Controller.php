<?php

namespace Front\Main;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Websm\Framework\Di\Container as Di;

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

        $data = [];

        $html = $this->render(__DIR__ . '/temp/default.tpl', $data);

        $this->layout
            ->setSrc('index')
            ->setContent($html);
    }
}
