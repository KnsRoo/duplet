<?php

namespace Back\Catalog\API\V1\Factory\HAL;

class Product {

    public static function getLinks($params) {

        $baseUrl = $params['baseUrl'];
        $item = $params['item'];

        return [
            'self' => [
                'href' => $baseUrl . '/products/' . $item['id'],
            ],
            'props' => [
                'href' => $baseUrl . '/products/' . $item['id'] . '/props',
            ],
            'products' => [
                'href' => $baseUrl . '/products',
            ],
        ];
    }

    public static function getEmbedded($params) {

        return [];

    }
}
