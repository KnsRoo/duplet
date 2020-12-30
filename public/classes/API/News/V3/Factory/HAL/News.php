<?php

namespace API\News\V3\Factory\HAL;

use Core\Router\Router;

class News {

    public static function getLinks($params) {

        $offset = $params['offset'];
        $limit = $params['limit'];
        $total = $params['total'];

        $origin = 'https://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'news' => Router::byName('api:news:v3:news')
                ->getAbsolutePath(),
            'api-base' => Router::byName('api:news:v3')
                ->getAbsolutePath(),
        ];

        $selfRef = $origin . $routes['news'];

        parse_str($_SERVER['QUERY_STRING'], $query);

        $query['offset'] = 0;
        $query['limit'] = $limit;
        $queryStr = urldecode(http_build_query($query));
        $queryStr = $queryStr ? '?' . $queryStr : '';
        $firstUrl = $selfRef . $queryStr;

        $query['offset'] = floor($total/max($limit, 1)) * $limit;
        $query['limit'] = $limit;
        $queryStr = urldecode(http_build_query($query));
        $queryStr = $queryStr ? '?' . $queryStr : '';
        $lastUrl = $selfRef . $queryStr;

        $apiBaseUrl = 'https://' . $_SERVER['SERVER_NAME'] . $routes['api-base'];

        $links = [
            'self' => [
                'href' => $selfRef,
            ],
            'first' => [
                'href' => $firstUrl,
            ],
            'last' => [
                'href' => $lastUrl,
            ],
            'curies' => [
                [
                    'name' => 'doc',
                    'href' => $apiBaseUrl . '/doc/rels/{rel}',
                    'templated' => true,
                ],
            ],
            'doc:news' => [
                'href' => $routes['news'],
            ],
            'doc:news-item' => [
                'href' => $routes['news'] . '/{id}',
                'templated' => true,
            ],
        ];

        if ($offset - $limit >= 0) {

            $query['offset'] = $offset - $limit;
            $query['limit'] = $limit;
            $queryStr = urldecode(http_build_query($query));
            $queryStr = $queryStr ? '?' . $queryStr : '';
            $prevUrl = $selfRef . $queryStr;

            $links['prev'] = [
                'href' => $prevUrl,
            ];
        }

        if ($offset + $limit < $total) {

            $query['offset'] = $offset + $limit;
            $query['limit'] = $limit;
            $queryStr = urldecode(http_build_query($query));
            $queryStr = $queryStr ? '?' . $queryStr : '';
            $nextUrl = $selfRef . $queryStr;

            $links['next'] = [
                'href' => $nextUrl,
            ];
        }

        return $links;
    }

    public static function getEmbedded($params) {

        $items = $params['items'];

        $result = [];

        foreach($items as $item)
            $result[] = NewsItem::get([
                'item' => $item
            ]);

        return [
            'items' => $result,
        ];
    }

    public static function get($params) {

        $offset = $params['offset'];
        $limit = $params['limit'];
        $total = $params['total'];
        $items = $params['items'];
        $size = count($items);

        return [
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total,
            'size' => $size,
            '_links' => self::getLinks([
                'offset' => $offset,
                'limit' => $limit,
                'total' => $total,
            ]),
            '_embedded' => self::getEmbedded([
                'items' => $items,
            ])
        ];
    }
}
