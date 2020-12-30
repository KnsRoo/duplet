<?php

namespace API\Auth\V1;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;

use Websm\Framework\Di;

class Controller extends Response {

    private $baseUrl;

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', [$this, 'getDescription'])
            ->setName('api:auth:v1');

        $services = new Services\Controller;
        $jwt = new JWT\Controller;
        $sms = new SMS\Controller;

        $group->mount('/services', $services->getRoutes());
        $group->mount('/jwt', $jwt->getRoutes());
        $group->mount('/sms', $sms->getRoutes());

        return $group;
    }

    public function getDescription($req, $next) {

        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('api:auth:v1')
                ->getAbsolutePath(),
            'services' => Router::byName('api:auth:v1:services')
                ->getAbsolutePath(),
            'jwt' => Router::byName('api:auth:v1:jwt')
                ->getAbsolutePath(),
            'sms' => Router::byName('api:auth:v1:sms')
                ->getAbsolutePath(),
        ];

        $this->hal([
            '_links' => [
                'self' => [
                    'href' => $origin . $routes['self'],
                ],
                'services' => [
                    'href' => $origin . $routes['services'],
                ],
                'jwt' => [
                    'href' => $origin . $routes['jwt'],
                ],
                'sms' => [
                    'href' => $origin . $routes['sms'],
                ],
            ],
        ]);
    }
}
