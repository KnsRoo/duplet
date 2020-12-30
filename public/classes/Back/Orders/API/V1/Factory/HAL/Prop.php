<?php

namespace Back\Orders\API\V1\Factory\HAL;
use Back\Orders\API\V1\Factory\QueryParams;

use Websm\Framework\Exceptions\HTTP as HTTPException;

use Websm\Framework\Router\Router;

class Prop {

    public static function getLinks($params) {

        $item = $params['item'];
        $name = $params['name'];
        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('api:orders:v1:order:prop')
                ->getAbsolutePath([
                    'id' => $item->id,
                    'name' => $name,
                ]),
        ];

        return [
            'self' => [
                'href' => $origin . $routes['self'],
            ],
        ];
    }

    public static function getEmbedded($params) {

        return [];
    }

    public static function get($params) {

        $item = $params['item'];
        $name = $params['name'];


        $props = json_decode($item->props);

        $prop = $props->$name;
        $result = $prop;

        $result->_links = self::getLinks(['item' => $item, 'name' => $name]);
        $result->_embedded = self::getEmbedded(['item' => $item, 'name' => $name]);

        return $result;
    }
}
