<?php

namespace Back\Catalog\API\V1\Factory\HAL;

class Prop {

    public static function getLinks($params) {

        $basePath = $params['basePath'];
        $productId = $params['productId'];
        $item = $params['item'];
        $name = $params['name'];

        $links = [
            'self' => [
                'href' => $basePath . '/products/' . $productId . '/props/' . $name,
            ],
            'props' => [
                'href' => $basePath . '/products/' . $productId . '/props',
            ],
            'product' => [
                'href' => $basePath . '/products/' . $productId,
            ],
        ];

        return $links;
    }

    public static function getEmbedded($params) {

        $embedded = [];
        return [];

    }
}
