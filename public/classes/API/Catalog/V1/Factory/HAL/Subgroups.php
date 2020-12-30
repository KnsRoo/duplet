<?php

namespace API\Catalog\V1\Factory\HAL;


class Subgroups {

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

    public static function getEmbedded($params) {

        $items = $params['items'];
        $origin = $params['origin'];
        $baseUrl = $params['baseUrl'];
        $result = [];

        foreach($items as $item) {

            $links = Group::getLinks([
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
}
