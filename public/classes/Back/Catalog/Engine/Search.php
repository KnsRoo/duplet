<?php

namespace Back\Catalog\Engine;

use Websm\Framework\Search\EngineInterface;
use Websm\Framework\Di;
use Websm\Framework\Search\TypesEnum;
use Websm\Framework\Search\JSONResult;
use Websm\Framework\Search\EngineAbstract;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;

class Search extends EngineAbstract {

    const NAME = 'catalog';
    const TEMPLATES = __DIR__ . '/temp/';

    private $di;

    public function __construct() {

        $this->di = Di\Container::instance();

    }

    public function getName() {

        return self::NAME;

    }

    private function getQueryForProducts($query) {

        return \Model\Catalog\Product::find('`title` LIKE :query', ['query' => "%${query}%"])
            ->order(['sort', 'title']);

    }

    public function count($query) {

        $count = $this->getQueryForProducts($query)
            ->count();

        return $count;

    }

    private function findGroups($query) {

        $response = new Response;
        $ret = [];

        $catalogs = \Model\Catalog\Group::find('`title` LIKE :query', ['query' => '%'.$query.'%'])
            ->orWhere('`preview` LIKE :query', [])
            /* ->orWhere('`about` LIKE :query', []) */
            ->andWhere(['visible' => 1])
            ->order('`title` ASC')
            ->genAll();

        foreach ($catalogs as $catalog) {

            $data = [];
            $data['catalog'] = $catalog;
            $ret[] = $response->render(self::TEMPLATES . 'GroupResult.tpl', $data);

        }

        return $ret;

    }

    private function findProducts($query) {

        $di = $this->di;
        $response = new Response;
        $ret = [];

        $products = \Model\Catalog\Product::find('`title` LIKE :query', ['query' => '%'.$query.'%'])
            ->orWhere('`preview` LIKE :query', [])
            /* ->orWhere('`about` LIKE :query', []) */
            ->orWhere('`tags` LIKE :query', [])
            ->andWhere(['visible' => 1])
            ->group('id')
            ->getAll();

        foreach ($products as $product) {

            $data = [];
            $data['product'] = $product;
            $ret[] = $response->render(self::TEMPLATES . 'ProductResult.tpl', $data);

        }

        return $ret;

    }

    public function findAsHTML($query) {

        $response = new Response;

        $groups = $this->findGroups($query);
        $products = $this->findProducts($query);

        $data = [];
        $data = array_merge($data, $groups);
        $data = array_merge($data, $products);

        return $data;

    }

    public function findAsJSON($query) {

        $products = $this->getQueryForProducts($query)
            ->genAll();

        $ret = [];

        foreach ($products as $product) {

            $check = \Model\Catalog\Product::find(['id' => $product->id])->get();
            $cid = $check->cid;

            $resCid = $cid ? $cid : '';

            $href = 'catalog/cat-' . $resCid . '/update-product-' . $product->id;

            $item = new JSONResult;
            $item->title = $product->title;
            $item->about = $product->preview;
            $item->price = $product->price;
            $item->picture = $product->getPicture('150x150');
            $item->ref = $href;
            $ret[] = $item;

        }

        return $ret;

    }

}
