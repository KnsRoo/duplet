<?php

namespace API\Auth\V1\Services;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Websm\Framework\JWT\Client as JWTClient;
use Websm\Framework\Exceptions\HTTP as HTTPException;

use Websm\Framework\Di;

class Vkontakte extends Response {

    private $authParams;
    private $baseUrl;

    const AUTH_URL = 'https://oauth.vk.com/authorize';
    const ACCESS_TOKEN_URL = 'https://oauth.vk.com/access_token';
    const API_BASE_URL = 'https://api.vk.com/method/getProfiles';
    const API_VERSION = '5.90';

    private $clientId;
    private $redirectUrl;
    private $sharedSecret;

    public function __construct() {

        $di = Di\Container::instance();
        $api = $di->get('api:auth:v1');

        $this->clientId = &$params['service:vkontakte:client_id'];
        $this->sharedSecret = &$params['service:vkontakte:secure_key'];
    }

    public function hal($data) {

        header('Content-Type: application/hal+json');
        die(json_encode($data));
    }

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', function($req, $next) {

            $routes = [
                'tokens' => Router::byName('API>Auth>V1>Services>Vkontakte>Tokens')
                    ->getAbsolutePath()
            ];

            $protocol = 'http';

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                $protocol = 'https';

            $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

            $this->hal([
                'appId' => $this->clientId,
                '_links' => [
                    'self' => [
                        'href' => $origin . $_SERVER['REQUEST_URI'],
                    ],
                    'tokens' => [
                        'href' => $origin . $routes['tokens'],
                    ],
                ],
            ]);
        })->setName('API>Auth>V1>Services>Vkontakte');

        $group->addGet('/tokens', [$this, 'getTokens'])
            ->setName('API>Auth>V1>Services>Vkontakte>Tokens');

        return $group;
    }

    function getTokens($req, $next) {

        try {

            $vkUserId = $this->getUserId();

            if ($vkUserId !== null) { 

                $user = \Model\User::find()
                    ->andWhere('JSON_EXTRACT(ids, \'$.vkontakte\') = :vkUserId', [
                        'vkUserId' => $vkUserId,
                    ])->get();

                if ($user->isNew()) {
                    $user->props = json_encode((Object)null);
                    $serviceIds = (Object)json_decode($user->ids);
                    $serviceIds->vkontakte = $vkUserId;
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
                    ->andWhere('JSON_EXTRACT(ids, \'$.vkontakte\') = :vkUserId', [
                        'vkUserId' => $vkUserId,
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

        $session = []; 
        $member = FALSE; 
        $userId = null;
        $validKeys = [ 'expire', 'mid', 'secret', 'sid', 'sig' ]; 
        /* $appCookie = $_COOKIE['vk_app_' . $this->clientId]; */ 
        $appCookie = &$_GET['token']; 

        if ($appCookie) { 

            $session_data = explode ('&', $appCookie, 10); 
            foreach ($session_data as $pair) { 

                list($key, $value) = explode('=', $pair, 2); 
                if (empty($key) || empty($value) || !in_array($key, $validKeys)) 
                    continue; 

                $session[$key] = $value; 
            } 

            foreach ($validKeys as $key) { 
                if (!isset($session[$key])) return $member; 
            } 

            ksort($session); 

            $sign = ''; 
            foreach ($session as $key => $value)
                if ($key != 'sig')
                    $sign .= ($key.'='.$value); 

            $sign .= $this->sharedSecret; 
            $sign = md5($sign); 

            if ($session['sig'] == $sign && $session['expire'] > time())
                $userId = $session['mid'];
        } 

        return $userId;
    }
}
