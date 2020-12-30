<?php

namespace API\News\V2;
use Core\Router;

class Factory {

    public static function getNewsHalLinks($params) {

        $offset = $params['offset'];
        $limit = $params['limit'];
        $total = $params['total'];
        $baseUrl = $params['baseUrl'];
        $origin = $params['origin'];

        $requestURI = $_SERVER['REQUEST_URI'];
        $selfRef = $origin . $requestURI;

        $urlParts = parse_url($selfRef);
        $urlParts['query'] = array_key_exists('query', $urlParts) ? $urlParts['query'] : '';
        parse_str($urlParts['query'], $query);

        $query['offset'] = 0;
        $query['limit'] = $limit;
        $rawQuery = urldecode(http_build_query($query));
        $rawQuery = $rawQuery ? '?' . $rawQuery : '';
        $firstUrl = $baseUrl . '/news' . $rawQuery;

        $query['offset'] = floor($total/max($limit, 1)) * $limit;
        $query['limit'] = $limit;
        $rawQuery = urldecode(http_build_query($query));
        $rawQuery = $rawQuery ? '?' . $rawQuery : '';
        $lastUrl = $baseUrl . '/news' . $rawQuery;

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
                    'href' => $baseUrl . '/doc/rels/{rel}',
                    'templated' => true,
                ],
            ],
            'doc:news' => [
                'href' => $baseUrl . '/news',
            ],
            'doc:news-item' => [
                'href' => $baseUrl . '/news/{id}',
                'templated' => true,
            ],
        ];

        if ($offset - $limit > 0) {

            $query['offset'] = $offset - $limit;
            $query['limit'] = $limit;
            $rawQuery = urldecode(http_build_query($query));
            $rawQuery = $rawQuery ? '?' . $rawQuery : '';
            $prevUrl = $baseUrl . '/news' . $rawQuery;

            $links['prev'] = [
                'href' => $prevUrl,
            ];
        }

        if ($offset + $limit < $total) {

            $query['offset'] = $offset + $limit;
            $query['limit'] = $limit;
            $rawQuery = urldecode(http_build_query($query));
            $rawQuery = $rawQuery ? '?' . $rawQuery : '';
            $nextUrl = $baseUrl . '/news' . $rawQuery;

            $links['next'] = [
                'href' => $nextUrl,
            ];
        }

        return $links;

    }

    public static function getNewsHalEmbedded($params) {

        $items = $params['items'];
        $origin = $params['origin'];
        $baseUrl = $params['baseUrl'];
        $result = [];

        foreach($items as $item) {
            $links = self::getNewsItemHalLinks([
                'item' => $item,
                'origin' => $origin,
                'baseUrl' => $baseUrl,
            ]);
            $item['_links'] = $links;
            $result[] = $item;
        }

        return [
            'items' => $result,
        ];

    }

    public static function getNewsItemHalLinks($params) {

        $baseUrl = $params['baseUrl'];
        $item = $params['item'];

        return [
            'self' => [
                'href' => $baseUrl . '/news/' . $item['id'],
            ],
        ];

    }

    public static function getNewsItemHalEmbedded($item) {

        return [];

    }
}
