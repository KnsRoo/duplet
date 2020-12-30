<?php

namespace API\Feedback\V1\Factory\HAL;

use Websm\Framework\Router\Router;

class Answer {

    public static function getLinks($params) {

        $item = $params['item'];
        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('api:feedback:v1:answer')
                ->getAbsolutePath([ 'id' => $item->id ]),
            'question' => Router::byName('api:feedback:v1:question')
                ->getAbsolutePath([ 'id' => $item->question_id ]),
        ];

        return [
            'self' => [
                'href' => $origin . $routes['self'],
            ],
            'question' => [
                'href' => $origin . $routes['question'],
            ],
        ];
    }

    public static function getEmbedded($params) {

        return [];
    }

    public static function get($params) {

        $item = $params['item'];

        return [
            'id' => (String)$item->id,
            'message' => $item->message,
            'info' => json_decode($item->info),
            'creationDate' => $item->creation_date,
            'updateDate' => $item->update_date,
            '_links' => self::getLinks(['item' => $item]),
            '_embedded' => self::getEmbedded(['item' => $item]),
        ];
    }
}
