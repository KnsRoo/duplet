<?php

namespace API\Catalog\V1\Factory\HAL;

class Products {

    public static function getLinks($params) {

        $offset = $params['offset'];
        $limit = $params['limit'];
        $total = $params['total'];
        $origin = $params['origin'];
        $groupId = $params['groupId'];
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
        $firstUrl = $baseUrl . '/groups/' . $groupId . '/products' . $queryStr;

        $query['offset'] = floor($total/max($limit, 1)) * $limit;
        $query['limit'] = $limit;
        $queryStr = urldecode(http_build_query($query));
        $queryStr = $queryStr ? '?' . $queryStr : '';
        $lastUrl = $baseUrl . '/groups/' . $groupId . '/products' . $queryStr;

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
            'doc:products' => [
                'href' => $baseUrl . '/groups/' . $groupId . '/products',
            ],
            'doc:product' => [
                'href' => $baseUrl . '/groups/' . $groupId . '/products/{productId}',
                'templated' => true,
            ],
        ];

        if ($offset - $limit > 0) {

            $query['offset'] = $offset - $limit;
            $query['limit'] = $limit;
            $queryStr = urldecode(http_build_query($query));
            $queryStr = $queryStr ? '?' . $queryStr : '';
            $prevUrl = $baseUrl . '/groups/' . $groupId . '/products' . $queryStr;

            $links['prev'] = [
                'href' => $prevUrl,
            ];
        }

        if ($offset + $limit < $total) {

            $query['offset'] = $offset + $limit;
            $query['limit'] = $limit;
            $queryStr = urldecode(http_build_query($query));
            $queryStr = $queryStr ? '?' . $queryStr : '';
            $nextUrl = $baseUrl . '/groups/' . $groupId . '/products' . $queryStr;

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
        $groupId = $params['groupId'];
        $result = [];

        foreach($items as $item) {

            $links = Product::getLinks([
                'baseUrl' => $baseUrl,
                'item' => $item,
                'groupId' => $groupId,
            ]);

            $item['_links'] = $links;
            $result[] = $item;
        }

        return [
            'items' => $result,
        ];

    }

}
