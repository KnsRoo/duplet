<?php

namespace API\Interview\V1\Factory\HAL;

use Model\Interview\QuizModel;

use Websm\Framework\Router\Router;

class Quiz {

    public static function getLinks($params) {

        $item = $params['item'];
        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('api:interview:v1:quizs')
                ->getAbsolutePath([ 'id' => $item->id ]),
            'questions' => Router::byName('api:interview:v1:questions')
                ->getAbsolutePath([ 'id' => $item->id ]),
        ];

        return [
            'self' => [
                'href' => $origin . $routes['self'],
            ],
            'questions' => [
                'href' => $origin . $routes['questions'],
            ],
        ];
    }

    public static function getEmbedded($params) {

        return (Object)null;
    }

    public static function checkQuizAvailable($quizId) {
        return QuizModel::find(['id' => $quizId])
            ->get()
            ->isAvailable();
    }

    public static function get($params) {

        $item = $params['item'];

        return [
            'id' => $item->id,
            'title' => $item->title,
            'available' => self::checkQuizAvailable($item->id),
            'date' => $item->date,
            '_links' => self::getLinks([ 'item' => $item ]),
            '_embedded' => self::getEmbedded([ 'item' => $item ]),
        ];
    }
}
