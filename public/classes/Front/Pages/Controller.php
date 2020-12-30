<?php

namespace Front\Pages;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Websm\Framework\Di\Container as Di;

use Model\Page;

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

        $group->addGet('/:chpu+', [$this, 'getContent'])
            ->setName('page');

        return $group;
    }

    public function getContent($req)
    {
        $page = Page::find(['chpu' => '/' . $req['chpu']])
            ->get();

        if ($page->isNew()) {
            $this->render404();
            return;
        }

        $data = [
            'page' => $page,
        ];

        $html = $this->render(__DIR__ . '/temp/default.tpl', $data);

        $this->layout
            ->setSrc('index')
            ->setContent($html);
    }

    public function render404()
    {
        $this->code(404);

        $html = $this->render(__DIR__ . '/temp/404.tpl');

        $this->layout
           ->setContent($html);
    }
}
