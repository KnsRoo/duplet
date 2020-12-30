<?php

namespace API\User\V1\Factory\HAL;

use Websm\Framework\Router\Router;
use Websm\Framework\Di;

class Props {

    public static function getLinks($params) {

        $items = $params['items'];

        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('api:user:v1:props')
                ->getAbsolutePath(),
        ];

        return [
            'self' => [
                'href' => $origin . $routes['self'],
            ],
        ];
    }

    public static function get($params) {

        $items = $params['items'];

        $result = new \stdClass;

        foreach($items as $key => $value) {

            $result->$key = $value;
        }

        $result->_links = self::getLinks(['items' => $items]);

        return $result;
    }
}
