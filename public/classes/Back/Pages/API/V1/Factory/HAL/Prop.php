<?php

namespace Back\Pages\API\V1\Factory\HAL;

class Prop
{

    public static function getLinks($params)
    {
        $basePath = $params['basePath'];
        $pageId = $params['pageId'];
        $item = $params['item'];
        $name = $params['name'];

        $links = [
            'self' => [
                'href' => $basePath . '/pages/' . $pageId . '/props/' . $name,
            ],
            'props' => [
                'href' => $basePath . '/pages/' . $pageId . '/props',
            ],
            'page' => [
                'href' => $basePath . '/pages/' . $pageId,
            ],
        ];

        return $links;
    }

    public static function getEmbedded($params)
    {
        $embedded = [];
        return [];
    }
}
