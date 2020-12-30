<?php

namespace Back\Users;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Back\Layout\LayoutModel;
use Core\User;
use Core\Users as PanelUsers;
use Websm\Framework\Notify;
use Websm\Framework\Validator;
use Core\ModuleInterface;

class Users extends Response implements ModuleInterface {

    public function __construct(&$props = []) { }

    public function setSettings(Array &$props = []) { }

    public function getSettings() { return []; }

    public function getRoutes() {

        $group = Router::group();

        $group->addPut('/update-user/:user', [$this, 'updateUser'])
            ->setName('Users.updateUser');

        $group->addDelete('/delete-user/:user', [$this, 'deleteUser'])
            ->setName('Users.deleteUser');

        $group->addGet('/edit-user/:user', [$this, 'editUser'])
            ->setName('Users.editUser');

        $group->addPost('/create-new-user', [$this, 'createUser'])
            ->setName('Users.createUser');

        $group->addGet('/', [$this, ['baseAction']])
            ->setName('Users.baseAction');

        return $group;

    }

    public function baseAction() { }

    public function updateUser($req) {

        $form = new UpdateUserForm();
        $form->bind($_POST['update-user']);
        $form->permitions = isset($_POST['permitions'])
            ? $_POST['permitions'] : [];

        if ($form->validate()) {

            $user = [];
            $user['login'] = $form->login;
            $user['password'] = $form->password;
            $user['phone'] = $form->phone;
            $user['email'] = $form->email;
            $user['modules'] = $form->modules;

            $user = new User($user);

            if ($user->exists(_PL)) {

                $user->save(_PL);
                Notify::push('Пользователь успешно сохранён.', 'ok');

                $this->location(Router::byName('Users.baseAction')->getAbsolutePath());

            }
            else Notify::push('Пользователь не существует.', 'err');

        }
        else {

            Notify::push('При сохранении пользователя произошли ошибки.', 'err');
            foreach ($form->getErrors() as $error)
                Notify::pushArray($error);

        }

        $this->back();

    }

    public function deleteUser($req) {

        if (PanelUsers::exists($req['user'])) {

            $result = PanelUsers::deleteUser($req['user']);

            if ($result) Notify::push('Пользователь удалён.', 'ok');
            else Notify::push('Ошибка удаления пользователя.', 'err');

        }

        else Notify::push('Пользователь не существует.', 'err');

        $this->back();

    }

    public function editUser($req, $next) {

        $this->editableUser = $req['user'];
        $next();

    }

    public function createUser() {

        $form = new NewUserForm();
        $form->bind($_POST['new-user']);
        $form->permitions = isset($_POST['permitions'])
            ? $_POST['permitions'] : [];

        if ($form->validate()) {

            $user = [];
            $user['login'] = $form->login;
            $user['password'] = $form->password;
            $user['phone'] = $form->phone;
            $user['email'] = $form->email;
            $user['modules'] = $form->modules;

            $user = new User($user);

            if ($user->exists(_PL))
                Notify::push('Пользователь уже существует.', 'err');

            else {

                $user->save(_PL);
                Notify::push('Пользователь успешно создан', 'ok');

            }

        }
        else {

            Notify::push('При создании пользователя произошли ошибки', 'err');
            foreach ($form->getErrors() as $error)
                Notify::pushArray($error);

        }

        $this->back();

    }

    public function init($req, $next) {

        $router = Router::instance();

        $router->mount('/', $this->getRoutes());

        $next();

    }

    public function getContent() {

        return $this->render(__DIR__.'/temp/default.tpl');

    }

    public function getSettingsContent($name = '', Array $permitions) { }

}
