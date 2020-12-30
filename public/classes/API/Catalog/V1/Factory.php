<?php

namespace API\Catalog\V1;

use Back\Catalog\Models\Groups as Group;

class Factory {

    static $hal = HAL;

    public static function getGroupsHalLinks($params) {

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
        $firstUrl = $baseUrl . '/groups' . $queryStr;

        $query['offset'] = floor($total/max($limit, 1)) * $limit;
        $query['limit'] = $limit;
        $queryStr = urldecode(http_build_query($query));
        $queryStr = $queryStr ? '?' . $queryStr : '';
        $lastUrl = $baseUrl . '/groups' . $queryStr;

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
            'doc:groups' => [
                'href' => $baseUrl . '/groups',
            ],
            'doc:group' => [
                'href' => $baseUrl . '/groups/{id}',
                'templated' => true,
            ],
        ];

        if ($offset - $limit > 0) {

            $query['offset'] = $offset - $limit;
            $query['limit'] = $limit;
            $queryStr = urldecode(http_build_query($query));
            $queryStr = $queryStr ? '?' . $queryStr : '';
            $prevUrl = $baseUrl . '/groups' . $queryStr;

            $links['prev'] = [
                'href' => $prevUrl,
            ];
        }

        if ($offset + $limit < $total) {

            $query['offset'] = $offset + $limit;
            $query['limit'] = $limit;
            $queryStr = urldecode(http_build_query($query));
            $queryStr = $queryStr ? '?' . $queryStr : '';
            $nextUrl = $baseUrl . '/groups' . $queryStr;

            $links['next'] = [
                'href' => $nextUrl,
            ];
        }

        return $links;

    }

    public static function getGroupsHalEmbedded($params) {

        $items = $params['items'];
        $origin = $params['origin'];
        $baseUrl = $params['baseUrl'];
        $result = [];

        foreach($items as $item) {

            $links = self::getGroupHalLinks([
                'baseUrl' => $baseUrl,
                'item' => $item,
            ]);

            $item['_links'] = $links;
            $result[] = $item;
        }

        return [
            'items' => $result,
        ];

    }

    public static function getGroupHalLinks($params) {

        $baseUrl = $params['baseUrl'];
        $item = $params['item'];

        return [
            'self' => [
                'href' => $baseUrl . '/groups/' . $item['id'],
            ],
            'products' => [
                'href' => $baseUrl . '/groups/' . $item['id'] . '/products',
            ],
            'subgroups' => [
                'href' => $baseUrl . '/groups/' . $item['id'] . '/subgroups',
            ],
        ];
    }

    public static function getGroupHalEmbedded($params) {

        return [];

    }

    public static function getSubgroupsHalLinks($params) {

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
        $firstUrl = $baseUrl . '/groups/' . $groupId . '/subgroups' . $queryStr;

        $query['offset'] = floor($total/max($limit, 1)) * $limit;
        $query['limit'] = $limit;
        $queryStr = urldecode(http_build_query($query));
        $queryStr = $queryStr ? '?' . $queryStr : '';
        $lastUrl = $baseUrl . '/groups/' . $groupId . '/subgroups' . $queryStr;

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
            'doc:subgroups' => [
                'href' => $baseUrl . '/groups/' . $groupId . '/subgroups',
            ],
        ];

        if ($offset - $limit > 0) {

            $query['offset'] = $offset - $limit;
            $query['limit'] = $limit;
            $queryStr = urldecode(http_build_query($query));
            $queryStr = $queryStr ? '?' . $queryStr : '';
            $prevUrl = $baseUrl . '/groups/' . $groupId . '/subgroups' . $queryStr;

            $links['prev'] = [
                'href' => $prevUrl,
            ];
        }

        if ($offset + $limit < $total) {

            $query['offset'] = $offset + $limit;
            $query['limit'] = $limit;
            $queryStr = urldecode(http_build_query($query));
            $queryStr = $queryStr ? '?' . $queryStr : '';
            $nextUrl = $baseUrl . '/groups/' . $groupId . '/subgroups' . $queryStr;

            $links['next'] = [
                'href' => $nextUrl,
            ];
        }

        return $links;

    }

    public static function getSubgroupsHalEmbedded($params) {

        $items = $params['items'];
        $origin = $params['origin'];
        $baseUrl = $params['baseUrl'];
        $result = [];

        foreach($items as $item) {

            $links = self::getGroupHalLinks([
                'baseUrl' => $baseUrl,
                'item' => $item,
            ]);

            $item['_links'] = $links;
            $result[] = $item;
        }

        return [
            'items' => $result,
        ];

    }

    public static function getProductsHalLinks($params) {

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
                'href' => $baseUrl . '/groups' . $groupId . '/products',
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


    public static function getProductsHalEmbedded($params) {

        $items = $params['items'];
        $origin = $params['origin'];
        $baseUrl = $params['baseUrl'];
        $origin = $params['origin'];
        $groupId = $params['groupId'];
        $result = [];

        foreach($items as $item) {

            $links = self::getProductHalLinks([
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

    public static function getProductHalLinks($params) {

        $baseUrl = $params['baseUrl'];
        $item = $params['item'];
        $groupId = $params['groupId'];

        return [
            'self' => [
                'href' => $baseUrl . '/groups/' . $groupId . '/products/' . $item['id'],
            ],
            'group' => [
                'href' => $baseUrl . '/groups/' . $groupId,
            ],
        ];
    }

    public static function getProductHalEmbedded($params) {

        return [];

    }
}
