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
    private $cart;

    public function __construct()
    {
        $this->di = Di::instance();
        $this->layout = $this->di->get('layout');
        $this->cart = $this->di->get('cart');
    }

    public function getRoutes()
    {
        $group = Router::group();

        $group->addGet('/', [$this, 'getDefault'])
            ->setName('cart:default');

        return $group;
    }

    public function getDefault($req)
    {

        $items = $this->cart->getItems();
        foreach($items as $item){
            $item->product = Product::find(['id' => $item->id])->get();
        }

        $data = [
            'items' => $items,
        ];

        \Components\Seo\Seo::setContent('Корзина - Оружейный магазин "Дуплет"');

        $html = $this->render(self::PATH . 'cart.tpl', $data);

        $this->layout
            ->setSrc('cart')
            ->setContent($html);
    }
}
