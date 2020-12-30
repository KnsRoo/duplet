<?php
 
namespace Back\Catalog\API;

use Websm\Framework\Router\Router;

use Websm\Framework\Di;

class Controller extends \Websm\Framework\Response {

    public function getRoutes() {

        $group = Router::group();

        $v1 = new V1\Controller;

        $group->mount('/v1', $v1->getRoutes());
        $group->mount('/', $v1->getRoutes());

        $group->addGet('/', function($req, $next) {

            $this->hal([
                '_links' => [
                    'self' => [
                        'href' => $this->origin . $_SERVER['REQUEST_URI'],
                    ],
                    'v1' => [
                        'href' => $this->basePath . '/v1',
                    ],
                ],
            ]);
        });

        return $group;

    }

    public function hal($payload) {

        header('Content-Type: application/hal+json');
        die(json_encode($payload));
    }
}
