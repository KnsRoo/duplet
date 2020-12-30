<?php

namespace API\Catalog\V1\Factory\HAL;

class Product {

    public static function getLinks($params) {

        $baseUrl = $params['baseUrl'];
        $item = $params['item'];
        $groupId = $params['groupId'];

        return [
            'self' => [
                'href' => $baseUrl . '/groups/' . $groupId . '/products/' . $item['id'],
            ],
            'group' => [
                'href' => $baseUrl . '/groups/' . $groupId,
            ],
        ];
    }

    public static function getEmbedded($params) {

        return [];

    }
}
