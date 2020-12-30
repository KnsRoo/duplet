<?php

namespace API\Auth\V1\Services;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;

use Websm\Framework\Di;

class Controller extends Response {

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', function($req, $next) {

            $routes = [
                /* 'vkontakte' => Router::byName('api:auth:v1:services:vkontakte') */
                /*     ->getAbsolutePath(), */
                /* 'odnoklassniki' => Router::byName('api:auth:v1:services:odnoklassniki') */
                /*     ->getAbsolutePath(), */
                /* 'google' => Router::byName('api:auth:v1:services:google') */
                /*     ->getAbsolutePath(), */
                /* 'facebook' => Router::byName('api:auth:v1:services:facebook') */
                /*     ->getAbsolutePath(), */
            ];

            $protocol = 'http';

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                $protocol = 'https';

            $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

            $this->hal([
                'self' => [
                    'href' => $origin . $_SERVER['REQUEST_URI'],
                ],
                /* 'vkontakte' => [ */
                /*     'href' => $origin . $routes['vkontakte'], */
                /* ], */
                /* 'odnoklassniki' => [ */
                /*     'href' => $origin . $routes['odnoklassniki'], */
                /* ], */
                /* 'google' => [ */
                /*     'href' => $origin . $routes['google'], */
                /* ], */
                /* 'facebook' => [ */
                /*     'href' => $origin . $routes['facebook'], */
                /* ], */
            ]);
        })->setName('api:auth:v1:services');

        /* $vkontakte = new Vkontakte; */
        /* $odnoklassniki = new Odnoklassniki; */
        /* $google = new Google; */
        /* $facebook = new Facebook; */

        /* $group->mount('/vkontakte', $vkontakte->getRoutes()); */
        /* $group->mount('/odnoklassniki', $odnoklassniki->getRoutes()); */
        /* $group->mount('/google', $google->getRoutes()); */
        /* $group->mount('/facebook', $facebook->getRoutes()); */

        return $group;
    }
}
