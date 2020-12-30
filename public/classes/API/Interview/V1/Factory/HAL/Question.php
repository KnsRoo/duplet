<?php

namespace API\Interview\V1\Factory\HAL;

use Model\Interview\Journal;

use Websm\Framework\Router\Router;

class Question {

    public static function getLinks($params) {

        $item = $params['item'];
        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('api:interview:v1:question')
                ->getAbsolutePath([ 'cid' => $item->quiz_id, 'id' => $item->id ]),
            'answers' => Router::byName('api:interview:v1:answers')
                ->getAbsolutePath([ 'cid' => $item->quiz_id, 'id' => $item->id ]),
        ];

        return [
            'self' => [
                'href' => $origin . $routes['self'],
            ],
            'answers' => [
                'href' => $origin . $routes['answers'],
            ],
        ];
    }

    public static function getEmbedded($params) {

        return (Object)null;
    }

    public static function get($params) {

        $item = $params['item'];

        return [
            'id' => $item->id,
            'title' => $item->title,
            'totalCount' => $item->getTotalVotes(),
            'date' => $item->date,
            '_links' => self::getLinks([ 'item' => $item ]),
            '_embedded' => self::getEmbedded([ 'item' => $item ]),
        ];
    }
}
