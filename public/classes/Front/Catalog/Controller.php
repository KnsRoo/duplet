<?php

namespace Front\Catalog;

use Model\Catalog\Group;
use Model\Catalog\Product;
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
            ->setName('catalog:default');

        $group->addGet('/', [$this, 'getDefault'])
            ->setName('catalog:group');
            
        $group->addGet('/product-:productId', [$this, 'getProduct'])
            ->setName('catalog:product');

        return $group;
    }

    public function getDefault($req)
    {

        \Components\Seo\Seo::setContent('Каталог товаров - Оружейный магазин "Дуплет"');

        $html = $this->render(self::PATH . 'default.tpl');

        $this->layout
            ->setSrc('catalog')
            ->setContent($html);
    }

    public function getProduct($req)
    {
        $data = [
            'link' => Router::byName('api:catalog:v3:product')
                    ->getURL(['id' => $req['productId']])
        ];

        $product = Product::find(['id' => $req['productId']])->get();

        \Components\Seo\Seo::setContent($product->title, $product->keywords, $product->preview);

        $html = $this->render(self::PATH . 'product.tpl', $data);

        $this->layout
            ->setSrc('product')
            ->setContent($html);
    }
}
