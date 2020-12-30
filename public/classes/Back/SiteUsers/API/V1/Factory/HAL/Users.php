<?php

namespace Back\SiteUsers\API\V1\Factory\HAL;
use Back\SiteUsers\API\V1\Factory\QueryParams;

use Websm\Framework\Router\Router;

class Users {

    public static function getLinks($params) {

        $offset = $params['offset'];
        $limit = $params['limit'];
        $total = $params['total'];
        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('api:siteusers:v1:users')
                ->getAbsolutePath(),
            'api-base' => Router::byName('api:siteusers:v1')
                ->getAbsolutePath(),
            'user' => Router::byName('api:siteusers:v1:user')
                ->getAbsolutePath(['id' => '{id}']),
        ];

        $selfUrl = $origin . $routes['self'];
        $baseUrl = $origin . $routes['api-base'];
        $userUrl = $origin . $routes['user'];

        $queryStr = &$_SERVER['QUERY_STRING'];
        parse_str($queryStr, $query);

        $query['offset'] = 0;
        $query['limit'] = $limit;
        $queryStr = urldecode(http_build_query($query));
        $queryStr = $queryStr ? '?' . $queryStr : '';
        $firstUrl = $selfUrl . $queryStr;

        $query['offset'] = floor($total/max($limit, 1)) * $limit;
        $query['limit'] = $limit;
        $queryStr = urldecode(http_build_query($query));
        $queryStr = $queryStr ? '?' . $queryStr : '';
        $lastUrl = $selfUrl . $queryStr;

        $links = [
            'self' => [
                'href' => $selfUrl,
            ],
            'first' => [
                'href' => $firstUrl,
            ],
            'last' => [
                'href' => $lastUrl,
            ],
            /* 'curies' => [ */
            /*     [ */
            /*         'name' => 'doc', */
            /*         'href' => $baseUrl . '/doc/rels/{rel}', */
            /*         'templated' => true, */
            /*     ], */
            /* ], */
            /* 'doc:users' => [ */
            /*     'href' => $selfUrl, */
            /* ], */
            /* 'doc:user' => [ */
            /*     'href' => $userUrl, */
            /*     'templated' => true, */
            /* ], */
        ];

        if ($offset - $limit >= 0) {

            $query['offset'] = $offset - $limit;
            $query['limit'] = $limit;
            $queryStr = urldecode(http_build_query($query));
            $queryStr = $queryStr ? '?' . $queryStr : '';
            $prevUrl = $selfUrl . $queryStr;

            $links['prev'] = [
                'href' => $prevUrl,
            ];
        }

        if ($offset + $limit < $total) {

            $query['offset'] = $offset + $limit;
            $query['limit'] = $limit;
            $queryStr = urldecode(http_build_query($query));
            $queryStr = $queryStr ? '?' . $queryStr : '';
            $nextUrl = $selfUrl . $queryStr;

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
            $result[] = User::get([ 'item' => $item ]);

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
            ]),
        ];
    }
}
