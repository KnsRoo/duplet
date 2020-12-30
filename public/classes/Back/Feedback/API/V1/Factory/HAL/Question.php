<?php

namespace Back\Feedback\API\V1\Factory\HAL;

use Websm\Framework\Router\Router;
use Back\Feedback\API\V1\Factory\QueryParams;

class Question {

    public static function getLinks($params) {

        $item = $params['item'];
        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('feedback:api:v1:question')
                ->getAbsolutePath([ 'id' => $item->id ]),
            'answers' => Router::byName('feedback:api:v1:answers')
                ->getAbsolutePath([ 'id' => $item->id ]),
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
            'userId' => $item->user_id,
            'info' => json_decode($item->info),
            'message' => $item->message,
            'sort' => (Integer)$item->sort,
            'visible' => (Boolean)$item->visible,
            'creationDate' => $item->creation_date,
            'updateDate' => $item->update_date,
            '_links' => self::getLinks([ 'item' => $item ]),
            '_embedded' => self::getEmbedded([ 'item' => $item ]),
        ];
    }
}
