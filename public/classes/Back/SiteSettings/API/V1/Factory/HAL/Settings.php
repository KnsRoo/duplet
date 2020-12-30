<?php

namespace Back\SiteSettings\API\V1\Factory\HAL;

use Websm\Framework\Router\Router;

class Settings {

    public static function getLinks($params) {

        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('site-settings:api:v1:settings')
                ->getAbsolutePath(),
        ];

        $selfUrl = $origin . $routes['self'];

        $links = [
            'self' => [
                'href' => $selfUrl,
            ],
        ];

        return $links;
    }

    public static function getEmbedded($params) {

        $items = $params['items'];
        $result = [];

        foreach($items as $item)
            $result[] = Setting::get([ 'item' => $item ]);

        return [
            'items' => $result,
        ];
    }

    public static function get($params) {

        $items = $params['items'];

        return [
            '_links' => self::getLinks([
                'items' => $items,
            ]),
            '_embedded' => self::getEmbedded([
                'items' => $items,
            ]),
        ];
    }
}
