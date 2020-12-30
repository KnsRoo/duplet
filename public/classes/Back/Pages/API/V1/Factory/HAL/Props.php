<?php

namespace Back\Pages\API\V1\Factory\HAL;

class Props
{

    public static function getLinks($params)
    {
        $origin = $params['origin'];
        $pageId = $params['pageId'];
        $basePath = $params['basePath'];

        $requestURI = $_SERVER['REQUEST_URI'];
        $selfRef = $origin . $requestURI;

        $links = [
            'self' => [
                'href' => $selfRef,
            ],
            'property' => [
                'href' => $basePath . '/pages/' . $pageId . '/props/{name}',
                'templated' => true,
            ],
            'properties' => [
                'href' => $basePath . '/pages/' . $pageId . '/props',
                'templated' => true,
            ],
            'page' => [
                'href' => $basePath . '/pages/' . $pageId,
                'templated' => true,
            ],
        ];

        return $links;
    }


    public static function getEmbedded($params)
    {
        $items = $params['items'];
        $origin = $params['origin'];
        $basePath = $params['basePath'];
        $pageId = $params['pageId'];
        $result = [];

        foreach ($items as $key => $item) {

            $links = Prop::getLinks([
                'origin' => $origin,
                'basePath' => $basePath,
                'item' => $item,
                'pageId' => $pageId,
                'name' => $key,
            ]);

            $result[] = [
                'name' => $key,
                'content' => $item,
                '_links' => $links,
            ];
        }

        $embedded = [
            'items' => $result,
        ];

        return $embedded;
    }
}
