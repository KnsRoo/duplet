<?php

namespace Back\SiteSettings\API\V1\Factory\HAL;

use Websm\Framework\Router\Router;

class Setting {

    public static function getLinks($params) {

        $item = $params['item'];
        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('site-settings:api:v1:setting')
                ->getAbsolutePath([ 'name' => $item->name ]),
            'content' => Router::byName('site-settings:api:v1:setting:content')
                ->getAbsolutePath([ 'name' => $item->name ]),
        ];

        return [
            'self' => [
                'href' => $origin . $routes['self'],
            ],
            'content' => [
                'href' => $origin . $routes['content'],
            ],
        ];
    }

    public static function getEmbedded($params) {

        return (Object)null;
    }

    public static function get($params) {

        $item = $params['item'];

        return [
            'id' => (String)$item->id,
            'name' => (String)$item->name,
            'type' => (String) $item->type,
            'content' => json_decode($item->content),
            '_links' => self::getLinks(['item' => $item]),
            '_embedded' => self::getEmbedded(['item' => $item]),
        ];
    }
}
