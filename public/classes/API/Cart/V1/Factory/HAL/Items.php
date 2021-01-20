<?php

namespace API\Cart\V1\Factory\HAL;

use Websm\Framework\Router\Router;

class Items {

    public static function getLinks($params) {

        $routes = [
            'self' => Router::byName('api:cart:v1:items')
                ->getAbsolutePath(),
            'base' => Router::byName('api:cart:v1')
                ->getAbsolutePath(),
        ];

        $links = [
            'self' => [
                'href' => Router::byName('api:cart:v1:items')
                    ->getURL(),
            ],
            'curies' => [
                [
                    'name' => 'doc',
                    'href' => Router::byName('api:cart:v1:docs')
                        ->getURL(),
                    'templated' => true,
                ],
            ],
            'doc:items' => [
                'href' => Router::byName('api:cart:v1:items')
                    ->getAbsolutePath(),
            ],
            'doc:item' => [
                'href' => Router::byName('api:cart:v1:item')
                    ->getAbsolutePath(),
                'templated' => true,
            ],
        ];

        return $links;
    }

    public static function getEmbedded($params) {

        $items = $params['items'];
        $statuses = $params['statuses'];
        $result = [];

        foreach($items as $item) {

            $item = Item::get([
                'item' => $item,
                'status' => $statuses[$item->product->id]
            ]);

            $result[] = $item;
        }

        return [
            'items' => $result,
        ];
    }

    public static function get($params) {

        $items = $params['items'];
        $statuses = $params['statuses'];

        return [
            '_links' => self::getLinks(['items' => $items]),
            '_embedded' => self::getEmbedded(['items' => $items, 'statuses' => $statuses]),
        ];
    }
}
