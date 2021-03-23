<?php

namespace Front\Contacts;

use Websm\Framework\Di\Container as Di;
use Websm\Framework\Response;
use Websm\Framework\Router\Router;

use Model\Page;

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
            ->setName('contacts:default');

        return $group;
    }

    public function getDefault($req)
    {

        $page = Page::find(['chpu' => '/Contacts'])->get();

        $shops = Page::find(['cid' => $page->id])->getAll();

        \Components\Seo\Seo::setContent($page->title, $page->keywords, $page->announce);

        $html = $this->render(self::PATH . 'contacts.tpl', ['shops' => $shops]);

        $this->layout
            ->setSrc('contacts')
            ->setContent($html);
    }
}
