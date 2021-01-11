<?php

namespace API\News\V4\Factory\HAL;

use Websm\Framework\Router\Router;

class Line {

    public static function getLinks($params) {

        $item = $params['item'];

        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('api:news:v4:line')
                ->getAbsolutePath(['id' => $item->id]),
            'lines' => Router::byName('api:news:v4:lines')
                ->getAbsolutePath(),
            'lineitems' => Router::byName('api:news:v4:lineitems')
                ->getAbsolutePath(['lid' => $item->id]),
        ];

        return [
            'self' => [
                'href' => $origin . $routes['self'],
            ],
            'lines' => [
                'href' => $origin . $routes['lines'],
            ],
            'lineitems' => [
                'href' => $origin . $routes['lineitems'],
            ],
        ];
    }

    public static function getEmbedded($params) {

        return [];
    }

    public static function get($params) {

        $item = $params['item'];

        $picSize = 500;
        $picture = $item->getPicture($picSize . 'x' .$picSize);
        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];
        $result = [
            'id' => $item->id,
            'title' => $item->title,
            'announce' => $item->announce,
            'picture' => $picture,
            'creationDate' => $item->date,
            'pageRef' => $origin . '/' . $item->chpu,
        ];

        $result['_links'] = self::getLinks(['item' => $item]);
        $result['_embedded'] = self::getEmbedded(['item' => $item]);

        return $result;
    }
}
