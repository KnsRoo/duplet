<?php

namespace API\Auth\V1\Basic;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Websm\Framework\JWT\Client as JWTClient;
use Websm\Framework\Exceptions\HTTP as HTTPException;
use Websm\Framework\Validation\Rules;
use Websm\Framework\Mail\HTMLMessage;

use Model\User; 
use Model\UserPhone; 
use Model\UserJWT;

use Websm\Framework\Di\Container as Di;

class Controller extends Response {

    private $validationSchema;
    private $verifyRecaptcha = false;

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', [$this, 'default'])
              ->setName('api:auth:v1:basic');

        $group->addPost('/confirm', [$this, 'confirm'])
              ->setName('api:auth:v1:basic:confirm');

        $group->addPost('/restore', [$this, 'restorePassword'])
              ->setName('api:auth:v1:basic:restore');

        $group->addPost('/restore/request', [$this, 'restoreRequest'])
              ->setName('api:auth:v1:basic:restore_request');

        $group->addPost('/valid', [$this, 'isValidLink'])
              ->setName('api:auth:v1:basic:valid');

        $group->addPost('/login' , [$this, 'login'])
              ->setName('api:auth:v1:basic:login');

        $group->addPost('/register' , [$this, 'registration'])
              ->setName('api:auth:v1:basic:register');

        return $group;
    }

    public function default(){
        $this->hal([
            "description" => "API Basic auth v1.0",
            "version" => "1.0",
            "updateDate" => "Dec, 2020",
            "author" => [ "name" => "KnsRoo",
                          "gitea" => "http://gitea.websm.io/KnsRoo"
                        ],
            "usage" => [
                "login" => [
                    "link" => Router::byName('api:auth:v1:basic:login')->getUrl(),
                    "required payload" => [ "email", "password" ],
                    "codes" => [
                        "200" => "OK",
                        "500" => "Account is not activated",
                        "501" => "User not found",
                        "422" => "login or password is invalid",
                    ],
                ],
                "register" => [
                    "link" => Router::byName('api:auth:v1:basic:register')->getUrl(),
                    "required payload" => [ "email", "password" ],
                    "codes" => [
                        "200" => "OK",
                        "500" => "Unable to save data",
                        "501" => "User already exists",
                    ],
                ],
                "confirm" => [
                    "link" => Router::byName('api:auth:v1:basic:confirm')->getUrl(),
                    "required payload" => [ "token", "id" ],
                    "codes" => [
                        "200" => "OK",
                        "500" => "Unable to save data",
                        "501" => "ConfirmToken is invalid",
                        "502" => "Token or id is not exists",
                    ],
                ],
                "restore request" => [
                    "link" => Router::byName('api:auth:v1:basic:restore_request')->getUrl(),
                    "required payload" => [ "email" ],
                    "codes" => [
                        "200" => "OK",
                        "500" => "Unable to save data",
                        "501" => "User not found",
                    ],
                ],
                "check valid link" => [
                    "link" => Router::byName('api:auth:v1:basic:valid')->getUrl(),
                    "required payload" => [ "token", "id" ],
                    "codes" => [
                        "200" => "OK",
                        "500" => "RestoreToken is not exists",
                        "501" => "User not found",
                        "502" => "RestoreToken is invalid",
                    ],
                ],
                "restore confirm" => [
                    "link" => Router::byName('api:auth:v1:basic:restore')->getUrl(),
                    "required payload" => [ "token", "id" ],
                    "codes" => [
                        "500" => "Unable to save data",
                        "501" => "User not found",
                        "502" => "Old password equal new password",
                    ]
                ],

            ],
            ]);
    }

    public function restorePassword(){
        try {
            $body = file_get_contents('php://input');
            $bodyArr = json_decode($body); 

            $user = User::find(['id' => $bodyArr->id])
                        ->get();

            if ($user->isNew()) {
                throw new HTTPException('Пользователя с таким email не существует', 501);
            }

            $di = Di::instance();
            $crypt = $di->get('crypt');

            $password = $crypt->encrypt($bodyArr->password);
            if ($user->password == $password){
                throw new HTTPException('Новый пароль должен отличаться от предыдущего', 502);
            }
            $user->password = $password;  

            $props = json_decode($user->props);

            unset($props->restoreToken);

            $user->props = json_encode($props);

            if (!$user->save())
                throw new HTTPException('Не удалось провести процедуру смены пароля', 500);

            $this->code(200);
            die();

        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());

            $this->hal([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ]
            ]);
        }

    }

    public function isValidLink(){
        try {
            $body = file_get_contents('php://input');
            $bodyArr = json_decode($body); 
            $user = User::find(['id' => $bodyArr->id])
                        ->get();
            if ($user->isNew()) {
                throw new HTTPException('Ссылка не действительна', 501);
            }

            $props = json_decode($user->props);

            if (!(array_key_exists('restoreToken', $props)))
                throw new HTTPException('Время жизни ссылки истекло', 500);

            if ($props->restoreToken != $bodyArr->token)
                throw new HTTPException('Время жизни ссылки истекло', 502);

            $this->code(200);
            die();

        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());

            $this->hal([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ]
            ]);
        }
    }

    public function restoreRequest(){
        try {
            $body = file_get_contents('php://input');
            $bodyArr = json_decode($body);  

            $di = Di::instance();
            $crypt = $di->get('crypt');

            $user = User::find(['email' => $crypt->encrypt($bodyArr->email)])
                        ->get();

            if ($user->isNew()) {
                throw new HTTPException('Пользователя с таким email не существует', 501);
            }

            $restoreToken = hash('sha256',$bodyArr->email.uniqid());

            $user->props = json_encode([
                'restoreToken' => $restoreToken
            ]);

            if (!$user->save())
                throw new HTTPException('Не удалось идентифицировать пользователя', 500);

            $domain = $_SERVER['SERVER_NAME'];

            $link = 'https://'.$domain.'/lk?mode=restore&token='.$restoreToken.'&id='.$user->id;

            $body = $this->render(__DIR__ . '/temp/restore.tpl', ['title' => 'ФИТО-ФАРМ', 'domain' => $domain, 'link' => $link]);

            $mail = new HTMLMessage;
            $mail->setFrom('<noreply@ffarm.ru>')
                ->setTo('<' . $bodyArr->email . '>')
                ->setSubject('Подтверждение смены пароля на сайте ' . $domain)
                ->setBody($body);

            $mail->send();

            $this->code(200);
            die();

        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());

            $this->hal([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ]
            ]);
        }

    }

    public function confirm(){
        try {
            $body = file_get_contents('php://input');
            $bodyArr = json_decode($body);

            $token = $bodyArr->token;
            $id = $bodyArr->id;

            if ($token == NULL || $id == NULL){
                throw new HTTPException('Неверный запрос', 502);
            }

            $user = User::find(['id' => $id])
                    ->get();

            $props = json_decode($user->props);

            if ($props->confirmToken != $token){
                throw new HTTPException('Неверный запрос', 501);
            }

            unset($props->confirmToken);

            $user->props = json_encode($props);

            if (!$user->save())
                throw new HTTPException('Не удалось подтвердить учетную запись', 500);

            $this->code(200);
            die();

        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());

            $this->hal([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ]
            ]);
        }
    }

    public function login(){
    	 try {



    		$body = file_get_contents('php://input');
            $bodyArr = json_decode($body);

            $client = new JWTClient;
            $di = Di::instance();
            $crypt = $di->get('crypt');


            $user = User::find(['email' => $crypt->encrypt($bodyArr->email)])
            			->get();

            if ($user->isNew()){
                throw new HTTPException('Пользователя с таким логином не существует', 501);
            }    

            $props = json_decode($user->props);


            if ($props && property_exists($props, 'confirmToken')){
                throw new HTTPException('Учетная запись не активирована', 500);
            }  

            if ($user->password !== $crypt->encrypt($bodyArr->password)){
            	throw new HTTPException('Неправильный логин или пароль', 422);
            }

            $email = $crypt->encrypt($bodyArr->email);

            $userJWT = UserJWT::find(['user_id' => $user->id])
                ->get();

            if ($userJWT->isNew())
                $userJWT->user_id = $user->id;

            $payload = [
                'userId' => $user->id,
                'site' => $_SERVER['HTTP_HOST'],
            ];

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

    public function registration(){

        try {

            $body = file_get_contents('php://input');
            $bodyArr = json_decode($body);

            $client = new JWTClient;

            $di = Di::instance();

    	    $crypt = $di->get('crypt');

    	    $email = $crypt->encrypt($bodyArr->email);
            $password = $crypt->encrypt($bodyArr->password);
            $confirmToken = hash('sha256',$bodyArr->email.uniqid());

            $user = User::find(['email' => $email])
                    ->get();

            if ($user->isNew()) {

                $user = new User();
                $user->email = $email;
                $user->password = $password;
                $user->props = json_encode([
                    'confirmToken' => $confirmToken
                ]);

                if (!$user->save())
                    throw new HTTPException('Не удалось идентифицировать пользователя', 500);

                $id = User::getDb()
                    ->lastInsertId();

                $domain = $_SERVER['SERVER_NAME'];

                $link = 'https://'.$domain.'/lk?mode=confirm&token='.$confirmToken.'&id='.$id;

                $body = $this->render(__DIR__ . '/temp/confirm.tpl', [ 'title' => 'Фито-фарм"', 'domain' => $domain, 'link' => $link]);

                $mail = new HTMLMessage;
                $mail->setFrom('<noreply@ffarm.ru>')
                    ->setTo('<' . $bodyArr->email . '>')
                    ->setSubject('Подтверждение регистрации на сайте ' . $domain)
                    ->setBody($body);

                $mail->send();

                $this->code(200);
                die();

                } else {
            	   throw new HTTPException('Пользователь с таким email уже существует', 501);
            }
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