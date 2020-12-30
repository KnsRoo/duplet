<?php

namespace API\Auth\V1\Services;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Websm\Framework\JWT\Client as JWTClient;
use Websm\Framework\Exceptions\HTTP as HTTPException;

use Websm\Framework\Di;

class Odnoklassniki extends Response {

    private $clientId;
    private $sharedSecret;

    public function __construct() {

        $di = Di\Container::instance();
        $api = $di->get('api:auth:v1');
        $params = $api->getParams();

        $this->clientId = &$params['service:odnoklassniki:client_id'];
        $this->sharedSecret = &$params['service:odnoklassniki:secure_key'];
    }

    public function hal($data) {

        header('Content-Type: application/hal+json');
        die(json_encode($data));
    }

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', function($req, $next) {

            $routes = [
                'tokens' => Router::byName('API>Auth>V1>Services>Odnoklassniki>Tokens')
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
        })->setName('API>Auth>V1>Services>Odnoklassniki');

        $group->addGet('/tokens', [$this, 'getTokens'])
            ->setName('API>Auth>V1>Services>Odnoklassniki>Tokens');

        return $group;
    }

    function getTokens($req, $next) {

        try {

            $odnoklasnikiUserId = $this->getUserId();

            if ($odnoklasnikiUserId !== null) { 

                $user = \Model\User::find()
                    ->andWhere('JSON_EXTRACT(ids, \'$.odnoklasniki\') = :odnoklasnikiUserId', [
                        'odnoklasnikiUserId' => $odnoklasnikiUserId,
                    ])->get();

                if ($user->isNew()) {
                    $user->props = json_encode((Object)null);
                    $serviceIds = (Object)json_decode($user->ids);
                    $serviceIds->odnoklasniki = $odnoklasnikiUserId;
                    $user->ids = json_encode($serviceIds);

                    if (!$user->save())
                        throw new HTTPException('user creation failed', 500);
                }

                $client = new JWTCLient;
                $tokens = $client->getTokens([
                    'user_id' => $user->id,
                    'site' => $_SERVER['SERVER_NAME'],
                ]);

                $user = \Model\User::find()
                    ->andWhere('JSON_EXTRACT(ids, \'$.odnoklasniki\') = :odnoklasnikiUserId', [
                        'odnoklasnikiUserId' => $odnoklasnikiUserId,
                    ])->get();

                $origin = 'https://' . $_SERVER['SERVER_NAME'];
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
        }
    }

    private function getUserId() {

        /* $token = &$_GET['token']; */
        /* $secret = $this->sharedSecret; */
        /* $accessToken = $this->clientId . "|" . $this->sharedSecret; */
        /* $url = "https://graph.facebook.com/debug_token?input_token=" . $token . "&access_token=" . $accessToken; */

        /* $ch = curl_init(); */
        /* curl_setopt($ch, CURLOPT_URL, $url); */
        /* curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); */
        /* $res = curl_exec($ch); */
        /* curl_close($ch); */      
        /* $res = json_decode($res); */
        /* $userId = $res->data->user_id; */
        /* $appId = $res->data->app_id; */

        /* if ($appId == $this->clientId) */
        /*     return $userId; */

        return null;
    }
}
