<?php

namespace API\DiscountCards\V1\Factory\HAL;

use Websm\Framework\Router\Router;

class Card {

    public static function getLinks($params) {

        $item = $params['item'];

        return [
            'self' => [
                'href' => Router::byName('api:discount-cards:v1:card')
                    ->getURL(['id' => $item->id]),
            ],
            'cards' => [
                'href' => Router::byName('api:discount-cards:v1:cards')
                    ->getURL(),
            ],
        ];
    }

    public static function getEmbedded($params) {

        return (Object)null;
    }

    public static function get($params) {

        $item = $params['item'];

        $result = [
            'id' => (Integer)$item->id,
            'code' => $item->code,
            'sum' => (Double)$item->sum,
        ];

        $result['_links'] = self::getLinks(['item' => $item]);
        $result['_embedded'] = self::getEmbedded(['item' => $item]);

        return $result;
    }
}
