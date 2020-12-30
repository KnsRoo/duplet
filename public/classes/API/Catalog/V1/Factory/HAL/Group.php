<?php

namespace API\Catalog\V1\Factory\HAL;

class Group {

    public static function getLinks($params) {

        $baseUrl = $params['baseUrl'];
        $item = $params['item'];

        return [
            'self' => [
                'href' => $baseUrl . '/groups/' . $item['id'],
            ],
            'products' => [
                'href' => $baseUrl . '/groups/' . $item['id'] . '/products',
            ],
            'subgroups' => [
                'href' => $baseUrl . '/groups/' . $item['id'] . '/subgroups',
            ],
        ];
    }

    public static function getEmbedded($params) {

        return [];

    }

}
