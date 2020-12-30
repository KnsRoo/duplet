<?php

namespace API\Feedback\V1\Factory\HAL;

use Websm\Framework\Router\Router;

class Question {

    public static function getLinks($params) {

        $item = $params['item'];
        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('api:feedback:v1:question')
                ->getAbsolutePath([ 'id' => $item->id ]),
            'answers' => Router::byName('api:feedback:v1:answers')
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

        $info = json_decode($item->info);
        unset($info->emails);
        unset($info->phones);

        return [
            'id' => $item->id,
            'userId' => $item->user_id,
            'info' => $info,
            'message' => $item->message,
            'creationDate' => $item->creation_date,
            'updateDate' => $item->update_date,
            '_links' => self::getLinks([ 'item' => $item ]),
            '_embedded' => self::getEmbedded([ 'item' => $item ]),
        ];
    }
}
