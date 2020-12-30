<?php

namespace Front\Cart;

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
            ->setName('catalog:default');

        $group->addGet('/group-:groupId', [$this, 'getGroup'])
            ->setName('catalog:group');

        $group->addGet('/product-:productId', [$this, 'getProduct'])
            ->setName('catalog:product');

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

        $html = $this->render(self::PATH . 'cart.tpl', $data);

        $this->layout
            ->setSrc('catalog')
            ->setContent($html);
    }

    public function getGroup($req)
    {
        $groupId = $req['groupId'];

        $groups = Group::find(['cid' => $groupId])
            ->andWhere(['visible' => true])
            ->order('`sort`')
            ->getAll();

        $data = [
            'groups' => $groups,
        ];

        $html = $this->render(self::PATH . 'group.tpl', $data);

        $this->layout
            ->setSrc('catalog')
            ->setContent($html);
    }

    public function getProduct($req)
    {
        $productId = $req['productId'];
        $product = Product::find(['id' => $productId])
            ->get();

        $data = [
            'product' => $product,
        ];

        $html = $this->render(self::PATH . 'product.tpl', $data);

        $this->layout
            ->setSrc('catalog')
            ->setContent($html);
    }
}
