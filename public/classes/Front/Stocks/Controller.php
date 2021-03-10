<?php

namespace Front\Stocks;

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
            ->setName('stocks:default');

        return $group;
    }

    public function getDefault($req)
    {
        $groups = Group::find()
            ->andWhere(['visible' => true])
            ->order('`sort`')
            ->getAll();

        $data = [
            'groups' => $groups,
        ];

        $html = $this->render(self::PATH . 'stocks.tpl', $data);

        $this->layout
            ->setSrc('stocks')
            ->setContent($html);
    }
}
