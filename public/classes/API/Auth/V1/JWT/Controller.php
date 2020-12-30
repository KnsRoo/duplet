<?php

namespace API\Auth\V1\JWT;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Websm\Framework\JWT\Client as JWTClient;
use Websm\Framework\Exceptions\HTTP as HTTPException;

use Websm\Framework\Di;

class Controller extends Response {

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', function($req, $next) {

            $route = Router::byName('api:auth:v1:jwt')
                ->getAbsolutePath();

            $protocol = 'http';

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                $protocol = 'https';

            $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

            $this->hal([
                '_links' => [
                    'self' => [
                        'href' => $origin . $_SERVER['REQUEST_URI'],
                    ],
                    'refresher' => [
                        'href' => $origin . $route . '/refresher',
                    ],
                    'decoder' => [
                        'href' => $origin . $route . '/decoder',
                    ],
                ],
            ]);
        })->setName('api:auth:v1:jwt');

        $group->addPost('/refresher', function($req, $next) {

            $client = new JWTClient;
            $refreshToken = &$_GET['token'];

            try {

                $data = $client->decodeToken($refreshToken);
                $data = json_decode($data);

                if (!property_exists($data, "userId"))
                    throw new HTTPException('invalid token specified: no user id', 400);

                $user = \Model\User::find([ 'id' => $data->userId ])
                    ->get();

                if ($user->isNew())
                    throw new HTTPException('invalid token specified: existing user not found', 400);

                $userJWT = \Model\UserJWT::find([ 'user_id' => $user->id ])
                    ->get();

                if ($userJWT->isNew())
                    throw new HTTPException('issued jwt not found', 400);

                if ($userJWT->refresh_token !== $refreshToken)
                    throw new HTTPException('invalid token specified: doesn\'t match existing token', 400);

                $tokens = $client->refreshToken($refreshToken);

                $userJWT->refresh_token = $tokens->refreshToken;
                $userJWT->save();

                $this->hal(
                    Factory\HAL\Tokens::get(['item' => $tokens])
                );

            } catch(HTTPException $e) {

                $this->code($e->getHttpCode());
                $this->json([
                    'errors' => [
                        [ 'message' => $e->getMessage() ],
                    ],
                ]);
            }
        })->setName('api:auth:v1:jwt:refresher');

        $group->addPost('/decoder', function($req, $next) {

            $client = new JWTClient;
            $token = &$_GET['token'];

            try {

                $data = $client->decodeToken($token);
                $this->json($data);
            } catch(HTTPException $e) {

                $this->code($e->getHttpCode());
                $this->json([
                    'errors' => [
                        [ 'message' => $e->getMessage() ],
                    ],
                ]);
            }
        })->setName('api:auth:v1:jwt:decoder');

        return $group;
    }
}
