<?php

namespace Back\Orders\API\V1\Factory\HAL;

use Websm\Framework\Router\Router;

class Props {

    public static function getLinks($params) {

        $item = $params['item'];
        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('api:orders:v1:order:props')
                ->getAbsolutePath([ 'id' => $item->id ]),
            'order' => Router::byName('api:orders:v1:order')
                ->getAbsolutePath([ 'id' => $item->id ]),
        ];

        $links = [
            'self' => [
                'href' => $origin . $routes['self'],
            ],
            'order' => [
                'href' => $origin . $routes['order'],
            ],
        ];

        return $links;
    }

    public static function getEmbedded($params) {

        return [];
    }

    public static function get($params) {

        $item = $params['item'];

        $result = [];

        $props = json_decode($item->props, true);
        foreach($props as $key => $value)
            $props[$key] = Prop::get([
                'item' => $item,
                'name' => $key,
            ]);

        $result = $props;
        $result['_links'] = self::getLinks([ 'item' => $item ]);
        $result['_embedded'] = self::getEmbedded([ 'item' => $item ]);

        return $result;
    }
}
