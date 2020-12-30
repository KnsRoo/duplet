<?php

namespace API\Orders\V1\Services;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;

class Controller extends Response {

    public function getRoutes() {

        $group = Router::group();

        $sberbank = new Sberbank;
        $group->mount('/sberbank', $sberbank->getRoutes());

        $group->addGet('/', function($req, $next) {

            $protocol = 'http';

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                $protocol = 'https';

            $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

            $routes = [
                'self' => Router::byName('api:orders:v1:services')
                    ->getAbsolutePath(),
                'sberbank' => Router::byName('api:orders:v1:services:sberbank')
                    ->getAbsolutePath(),
            ];

            $this->json([
                '_links' => [
                    'self' => [
                        'href' => $origin . $routes['self'],
                    ],
                    'sberbank' => [
                        'href' => $origin . $routes['sberbank'],
                    ],
                ],
            ]);
        })->setName('api:orders:v1:services');

        return $group;
    }
}
