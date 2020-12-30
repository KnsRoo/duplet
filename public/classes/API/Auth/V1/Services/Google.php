<?php

namespace API\Auth\V1\Services;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Websm\Framework\JWT\Client as JWTClient;
use Websm\Framework\Exceptions\HTTP as HTTPException;

use Websm\Framework\Di;

class Google extends Response {

    private $clientId;
    private $sharedSecret;

    public function __construct() {

        $di = Di\Container::instance();
        $api = $di->get('api:auth:v1');
        $params = $api->getParams();

        $this->clientId = &$params['service:google:client_id'];
        $this->sharedSecret = &$params['service:google:secure_key'];
    }

    public function hal($data) {

        header('Content-Type: application/hal+json');
        die(json_encode($data));
    }

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', function($req, $next) {

            $routes = [
                'tokens' => Router::byName('API>Auth>V1>Services>Google>Tokens')
                    ->getAbsolutePath()
            ];

            $protocol = 'http';

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                $protocol = 'https';

            $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

            $this->hal([
                'clientId' => $this->clientId,
                '_links' => [
                    'self' => [
                        'href' => $origin . $_SERVER['REQUEST_URI'],
                    ],
                    'tokens' => [
                        'href' => $origin . $routes['tokens'],
                    ],
                ],
            ]);
        })->setName('API>Auth>V1>Services>Google');

        $group->addGet('/tokens', [$this, 'getTokens'])
            ->setName('API>Auth>V1>Services>Google>Tokens');

        return $group;
    }

    function getTokens($req, $next) {

        try {

            $googleUserId = $this->getUserId();

            if ($googleUserId !== null) { 

                $user = \Model\User::find()
                    ->andWhere('JSON_EXTRACT(ids, \'$.google\') = :googleUserId', [
                        'googleUserId' => $googleUserId,
                    ])->get();

                if ($user->isNew()) {
                    $user->props = json_encode((Object)null);
                    $serviceIds = (Object)json_decode($user->ids);
                    $serviceIds->google = $googleUserId;
                    $user->ids = json_encode($serviceIds);

                    if (!$user->save())
                        throw new HTTPException('user creation failed', 500);
                }

                $client = new JWTCLient;
                $tokens = $client->getTokens([
                    'user_id' => $user->id,
                    'site' => $_SERVER['SERVER_NAME'],
                ]);

                $origin = 'https://' . $_SERVER['SERVER_NAME'];

                $user = \Model\User::find()
                    ->andWhere('JSON_EXTRACT(ids, \'$.google\') = :googleUserId', [
                        'googleUserId' => $googleUserId,
                    ])->get();

                $routes = [
                    'refresher' => Router::byName('API>Auth>V1>JWT>Refresher')
                        ->getAbsolutePath(),
                    'decoder' => Router::byName('API>Auth>V1>JWT>Decoder')
                        ->getAbsolutePath(),
                    'user' => Router::byName('API>Users>V1>User')
                        ->getAbsolutePath([ 'id' => $user->id ]),
                ];

                $tokens['_links'] = [
                    'refresher' => [
                        'href' => $origin . $routes['refresher'],
                    ],
                    'decoder' => [
                        'href' => $origin . $routes['decoder'],
                    ],
                    'user' => [
                        'href' => $origin . $routes['user'],
                    ],
                ];

                $this->hal($tokens);

            } else throw new HTTPException('authentication failed', 412);

        } catch(HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ],
            ]);
        } catch(\Exception $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ],
            ]);
        }
    }

    private function getUserId() {

        // server time have to be synchronized
        // do not do like this!!!
        \Firebase\JWT\JWT::$leeway = 60;

        $userId = null;
        $token = &$_GET['token'];
        $client = new \Google_Client(['client_id' => $this->clientId]);
        $payload = $client->verifyIdToken($token);
        if ($payload)
            $userId = $payload['sub'];

        return $userId;
    }
}
