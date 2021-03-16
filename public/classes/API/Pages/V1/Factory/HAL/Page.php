<?php

namespace API\Pages\V1\Factory\HAL;

use Websm\Framework\Router\Router;

class Page {

    public static function getLinks($params) {

        $item = $params['item'];

        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('api:pages:v1:page')
                ->getAbsolutePath(['id' => $item->id]),
            'props' => Router::byName('api:pages:v1:props')
                ->getAbsolutePath(['id' => $item->id]),
            'subpages' => Router::byName('api:pages:v1:subpages')
                ->getAbsolutePath(['id' => $item->id]),
        ];

        return [
            'self' => [
                'href' => $origin . $routes['self'],
            ],
            'props' => [
                'href' => $origin . $routes['props'],
            ],
            'subpages' => [
                'href' => $origin . $routes['subpages'],
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

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $result = [
            'id' => $item->id,
            'title' => $item->title,
            'announce' => $item->announce,
            'text' => $item->text,
            'picture' => $picture,
            'creationDate' => $item->date,
            'pageRef' => $origin . $item->chpu,
            'tags' => $item->getTags(),
        ];

        $result['_links'] = self::getLinks(['item' => $item]);
        $result['_embedded'] = self::getEmbedded(['item' => $item]);

        return $result;
    }
}
