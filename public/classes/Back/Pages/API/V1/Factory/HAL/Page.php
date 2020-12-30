<?php

namespace Back\Pages\API\V1\Factory\HAL;

class Page
{

    public static function getLinks($params)
    {
        $baseUrl = $params['baseUrl'];
        $item = $params['item'];

        return [
            'self' => [
                'href' => $baseUrl . '/pages/' . $item['id'],
            ],
            'props' => [
                'href' => $baseUrl . '/pages/' . $item['id'] . '/props',
            ],
            'pages' => [
                'href' => $baseUrl . '/pages',
            ],
        ];
    }

    public static function getEmbedded($params)
    {
        return [];
    }
}
