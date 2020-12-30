<?php

namespace API\Auth\V1\SMS;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Websm\Framework\JWT\Client as JWTClient;
use Websm\Framework\Exceptions\HTTP as HTTPException;
use Websm\Framework\Validation\Rules;

use Websm\Framework\Di;

class Controller extends Response {

    private $validationSchema;
    private $verifyRecaptcha = true;

    public function __construct($params = []) {

        $this->validationSchema = $params['validationSchema'];
        $this->verifyRecaptcha = isset($params['verifyRecaptcha']) ? $params['verifyRecaptcha'] : $this->verifyRecaptcha;
    }

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', [$this, 'default'])
              ->setName('api:auth:v1:sms');

        $group->addPost('/code-sender' , [$this, 'sendCode'])
              ->setName('api:auth:v1:sms:code-sender');

        $group->addPost('/code-verifier' , [$this, 'verifyCode'])
              ->setName('api:auth:v1:sms:code-verifier');

        return $group;
    }

    public function default() {

        $route = Router::byName('api:auth:v1:sms')
            ->getAbsolutePath();

        $routes = [
            'self' => Router::byName('api:auth:v1:sms')
                ->getAbsolutePath(),
            'code-sender' => Router::byName('api:auth:v1:sms:code-sender')
                ->getAbsolutePath(),
            'code-verifier' => Router::byName('api:auth:v1:sms:code-verifier')
                ->getAbsolutePath(),
        ];

        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $di = Di\Container::instance();
        $grecaptcha = $di->get('grecaptcha');

        $this->hal([
            'validationSchema' => $this->validationSchema,
            'siteKey' => $grecaptcha->getSiteKey(),
            '_links' => [
                'self' => [
                    'href' => $origin . $routes['self'],
                ],
                'code-sender' => [
                    'href' => $origin . $routes['code-sender'],
                ],
                'code-verifier' => [
                    'href' => $origin . $routes['code-verifier'],
                ],
            ],
        ]);
    }

    public function sendCode($req, $next) {

        try {

            $body = file_get_contents('php://input');
            $bodyObj = json_decode($body);

            $token = '';
            if (key_exists('grecaptchaToken', $bodyObj))
                $token = $bodyObj->grecaptchaToken;

            $body = Factory\Filters\Body::filterInfo($body, $this->validationSchema);
            $body = json_decode($body);

            $di = Di\Container::instance();
            $grecaptcha = $di->get('grecaptcha');

            if ($this->verifyRecaptcha && !$grecaptcha->verify($token))
                throw new HTTPException('captcha vierification failed', 422);

            $phone = $body->phone;

            $di = Di\Container::instance();
            $nosql = $di->get('nosql');

            $smsGate = $di->get('sms-gate');

            if ($nosql->exists($phone))
                throw new HTTPException('code was already sent', 409);

            $code = '';

            foreach (range(1,6) as $index) {
                $code .= random_int(0, 9);
            }

            $content = [
                'code' => $code,
                'trys' => 3,
            ];

            $res = $nosql->set($phone, json_encode($content));
            $nosql->setTimeout($phone, 60 * 5);

            $domain = $_SERVER['HTTP_HOST'];
            $textMessage = 'Ваш проверочный код на сайте ' . $domain . ' - ' . $code;

            $message = new \Websm\Framework\Sms\Message($phone, $textMessage);
            $smsGate->send($message);

            $protocol = 'http';

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                $protocol = 'https';

            $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

            $client  = new JWTClient;
            $tokens = $client->getTokens([
                'phone' => $phone,
            ]);

            $routes = [
                'self' => Router::byName('api:auth:v1:jwt')
                    ->getAbsolutePath(),
                'refresher' => Router::byName('api:auth:v1:jwt:refresher')
                    ->getAbsolutePath(),
                'decoder' => Router::byName('api:auth:v1:jwt:decoder')
                    ->getAbsolutePath(),
            ];

            $this->hal(
                \API\Auth\V1\JWT\Factory\HAL\Tokens::get([ 'item' => $tokens ])
            );

        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());

            $this->hal([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ]
            ]);
        }
    }

    public function verifyCode($req, $next) {

        try {

            $jwt = &$_GET['jwt'];
            $client = new JWTClient;

            $payload = '';

            try {

                $payload = $client->decodeToken($jwt);
            } catch (HTTPException $e) {

                throw new HTTPException('invalid jwt', 409);
            }

            $payload = json_decode($payload);
            $phone = $payload->phone;

            $body = file_get_contents('php://input');
            $bodyObj = json_decode($body);

            $code = '';

            if (property_exists($bodyObj, 'code'))
                $code = $bodyObj->code;

            $di = Di\Container::instance();
            $nosql = $di->get('nosql');

            $content = $nosql->get($phone);

            if ($content === false)
                throw new HTTPException('code expired', 422);

            $content = json_decode($content);
            $codeVal = $content->code;

            if ($codeVal !== $code) {

                $content->trys = max(0, $content->trys - 1);
                $nosql->set($phone, json_encode($content));

                if ($content->trys == 0) {

                    throw new HTTPException('blocked', 422);
                } else {

                    throw new HTTPException('invalid code', 422);
                }
            }

            $nosql->delete($phone);

            $client = new JWTClient;

            $crypt = $di->get('crypt');
            $phone = $crypt->encrypt($phone);

            $user = \Model\User::find(['phone' => $phone])
                ->get();

            if ($user->isNew()) {

                $user->phone = $phone;

                if (!$user->save())
                    throw new HTTPException('unable to create user', 500);

                $id = \Model\User::getDb()
                    ->lastInsertId();

                $user = \Model\User::find(['id' => $id])
                    ->get();
            }

            $payload = [
                'userId' => $user->id,
                'site' => $_SERVER['HTTP_HOST'],
            ];

            $userJWT = \Model\UserJWT::find(['user_id' => $user->id])
                ->get();

            if ($userJWT->isNew())
                $userJWT->user_id = $user->id;

            $tokens = $client->getTokens($payload);

            $userJWT->refresh_token = $tokens->refreshToken;
            $userJWT->save();

            $this->hal(
                \API\Auth\V1\JWT\Factory\HAL\Tokens::get([ 'item' => $tokens ])
            );

        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());

            $this->hal([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ]
            ]);
        }
    }
}
