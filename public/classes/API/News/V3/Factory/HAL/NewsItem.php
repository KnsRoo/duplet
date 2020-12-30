<?php

namespace API\News\V3\Factory\HAL;

use Core\Router\Router;

class NewsItem {

    public static function getLinks($params) {

        $item = $params['item'];

        $origin = 'https://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'news-item' => Router::byName('api:news:v3:news-item')
                ->getAbsolutePath(['id' => $item->id]),
            'news' => Router::byName('api:news:v3:news')
                ->getAbsolutePath(),
            'api-base' => Router::byName('api:news:v3')
                ->getAbsolutePath(),
        ];

        return [
            'self' => [
                'href' => $origin . $routes['news-item'],
            ],
            'news' => [
                'href' => $origin . $routes['news'],
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

        $result = [
            'id' => $item->id,
            'title' => $item->title,
            'announce' => $item->announce,
            'picture' => $picture,
            'creationDate' => $item->date,
            'pageRef' => 'https://' . $_SERVER['SERVER_NAME'] . '/' . $item->chpu,
        ];

        $result['_links'] = self::getLinks(['item' => $item]);
        $result['_embedded'] = self::getEmbedded(['item' => $item]);

        return $result;
    }
}
