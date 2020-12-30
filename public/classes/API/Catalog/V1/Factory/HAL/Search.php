<?php

namespace API\Catalog\V1\Factory\HAL;

class Search {

    public static function getLinks($params) {

        $offset = $params['offset'];
        $limit = $params['limit'];
        $total = $params['total'];
        $origin = $params['origin'];
        $baseUrl = $params['baseUrl'];

        $requestURI = $_SERVER['REQUEST_URI'];
        $selfRef = $origin . $requestURI;

        $urlParts = parse_url($selfRef);
        $urlParts['query'] = array_key_exists('query', $urlParts) ? $urlParts['query'] : '';
        parse_str($urlParts['query'], $query);

        $query['offset'] = 0;
        $query['limit'] = $limit;
        $queryStr = urldecode(http_build_query($query));
        $queryStr = $queryStr ? '?' . $queryStr : '';
        $firstUrl = $baseUrl . '/search' . $queryStr;

        $query['offset'] = floor($total/max($limit, 1)) * $limit;
        $query['limit'] = $limit;
        $queryStr = urldecode(http_build_query($query));
        $queryStr = $queryStr ? '?' . $queryStr : '';
        $lastUrl = $baseUrl . '/search' . $queryStr;

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
            'doc:search' => [
                'href' => $baseUrl . '/search',
            ],
        ];

        if ($offset - $limit > 0) {

            $query['offset'] = $offset - $limit;
            $query['limit'] = $limit;
            $queryStr = urldecode(http_build_query($query));
            $queryStr = $queryStr ? '?' . $queryStr : '';
            $prevUrl = $baseUrl . '/search' . $queryStr;

            $links['prev'] = [
                'href' => $prevUrl,
            ];
        }

        if ($offset + $limit < $total) {

            $query['offset'] = $offset + $limit;
            $query['limit'] = $limit;
            $queryStr = urldecode(http_build_query($query));
            $queryStr = $queryStr ? '?' . $queryStr : '';
            $nextUrl = $baseUrl . '/search' . $queryStr;

            $links['next'] = [
                'href' => $nextUrl,
            ];
        }

        return $links;

    }


    public static function getEmbedded($params) {

        $items = $params['items'];
        $origin = $params['origin'];
        $baseUrl = $params['baseUrl'];
        $origin = $params['origin'];
        $cids = $params['cids'];
        $result = [];

        foreach($items as $item) {

            $links = Product::getLinks([
                'baseUrl' => $baseUrl,
                'item' => $item,
                'groupId' => $cids[$item['id']],
            ]);

            $item['_links'] = $links;
            $result[] = $item;
        }

        return [
            'items' => $result,
        ];

    }

}
