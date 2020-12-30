<?php

namespace Back\Auth;

use Websm\Framework\Router\Router;
use Websm\Framework\Di;
use Core\Users;
use Websm\Framework\Notify;

class Auth extends \Websm\Framework\Response {

    public function getRoutes() {

        $group = Router::group();

        $group->addPost('/remind', [$this, 'remind']);

        $group->addAll('/', [$this, 'isLockedWithAjax'], ['end' => false])
            ->withAjax();

        $group->addAll('/', [$this, 'isLocked'], ['end' => false]);

        $group->addPost('/login', function () {

            if (!$this->login()) $this->auth = true;

            $this->notify = Notify::shiftAll();
            $this->json();

        })->withAjax();

        $group->addAjax('/', function ($req, $next) {

            $user = AuthModel::get();

            $login = isset($user['login']) ? $user['login'] : '';

            if (Users::exists($login)) Users::set(Users::$_USERS[$login]);

            if (!AuthModel::isAuth() || !AuthModel::isCurrentSession(Users::get())) {

                $this->auth = true;
                $this->json();

            }

            $next();

        }, ['end' => false]);

        $group->addPost('/login', function () {

            $this->login();
            $this->back();

        });

        $group->addPost('/exit',[$this, 'userExit']);


        $group->addAll('/', [$this, 'showLoginForm'], ['end' => false]);

        return $group;

    }

    public function showLoginForm($req, $next) {

        $user = &AuthModel::get();
        $login = isset($user['login']) ? $user['login'] : '';

        if (Users::exists($login)) Users::set(Users::$_USERS[$login]);

        if (!AuthModel::isAuth() || !AuthModel::isCurrentSession(Users::get()))
            die($this->render(__DIR__.'/temp/default.tpl'));

        else $next();

    }

    public function remind() {

        $login = &$_POST['login'];

        if (Users::exists($login)) Users::set(Users::$_USERS[$login]);

        $user = Users::get();

        if ($user && ($phone = $user->phone)) {

            $di = Di\Container::instance();
            $smsClient = $di->get("sms-gate");
            $msg  = new \Websm\Framework\Sms\Message('7' . $phone, 'Пароль: '. $user->password);

            try {
                $smsClient->send($msg);
            } catch(\Websm\Framework\Exceptions\HTTP $e) {

                Notify::push('Ошибка при отправке sms', 'err');
            }

            Notify::push('Пароль был выслан на Ваш телефон.');

        }
        else Notify::push('Нет возможности напомнить пароль для данного пользователя.', 'err');

        $this->back();

    }

    public function userExit() {

        AuthModel::deAuth();
        Notify::push('Был совершен выход.');
        $this->back();
    
    }

    public function login() {

        $login = &$_POST['login'];
        if (Users::exists($login)) Users::set(Users::$_USERS[$login]);
        $user = Users::get();

        if ($user) {

            if ($user->password != AuthModel::getPass()) {

                AuthModel::decrTry();
                AuthModel::setBan();
                \Model\Journal::add('warning', 'Введен неверный пароль для входа в панель для логина: <b>'.$user->login.'</b>', 'Авторизация');
                Notify::push('Не верный пароль.', 'err');

            }

            else {

                AuthModel::setCurentSession($user);
                AuthModel::set($user);
                \Model\Journal::add('notice', 'Удачный вход в панель под логином: <b>'.$user->login.'</b>', 'Авторизация');
                AuthModel::rmBan();
                return true;

            }

        }

        else {

            AuthModel::decrTry();
            AuthModel::setBan();
            \Model\Journal::add('warning', 'Попытка входа под несуществующим пользователем: <b>'.$login.'</b>', 'Авторизация');
            Notify::push('Пользователь не существует.', 'err');

        }

    }

    public function isLockedWithAjax($req, $next) {

        if (AuthModel::isLocked()) {

            $this->actions = 'window.location.reload();';
            $this->json();

        } else $next();

    }

    public function isLocked($req, $next) {

        if (AuthModel::isLocked())
            die($this->render(__DIR__.'/temp/locked.tpl'));

        $next();

    }

}
