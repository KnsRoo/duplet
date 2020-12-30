<?php

namespace API\Pages\V1\Factory\HAL;

use Websm\Framework\Router\Router;

class Subpages {

    public static function getLinks($params) {

        $offset = $params['offset'];
        $limit = $params['limit'];
        $total = $params['total'];

        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('api:pages:v1:subpages')
                ->getAbsolutePath(),
        ];

        $selfRef = $origin . $routes['self'];

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
            $result[] = Page::get([
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
