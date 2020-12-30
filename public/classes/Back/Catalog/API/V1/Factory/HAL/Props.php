<?php

namespace Back\Catalog\API\V1\Factory\HAL;

class Props {

    public static function getLinks($params) {

        $origin = $params['origin'];
        $productId = $params['productId'];
        $basePath = $params['basePath'];

        $requestURI = $_SERVER['REQUEST_URI'];
        $selfRef = $origin . $requestURI;

        $links = [
            'self' => [
                'href' => $selfRef,
            ],
            'property' => [
                'href' => $basePath . '/products/' . $productId . '/props/{name}',
                'templated' => true,
            ],
            'properties' => [
                'href' => $basePath . '/products/' . $productId . '/props',
                'templated' => true,
            ],
            'product' => [
                'href' => $basePath . '/products/' . $productId,
                'templated' => true,
            ],
        ];

        return $links;

    }


    public static function getEmbedded($params) {

        $items = $params['items'];
        $origin = $params['origin'];
        $basePath = $params['basePath'];
        $productId = $params['productId'];
        $result = [];

        foreach($items as $key => $item) {

            $links = Prop::getLinks([
                'origin' => $origin,
                'basePath' => $basePath,
                'item' => $item,
                'productId' => $productId,
                'name' => $key,
            ]);

            $result[] = [
                'name' => $key,
                'content' => $item,
                '_links' => $links,
            ];
        }

        $embedded = [
            'items' => $result,
        ];

        return $embedded;

    }

}
