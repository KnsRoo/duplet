<?php

namespace Back\Users;

use Websm\Framework\Validator;
use Back\Layout\LayoutModel;

class UpdateUserForm extends Validator {

    public $modules = [];
    public $permitions = [];

    public function getRules() {

        return [
            ['login', 'required', 'message' => 'Логин должен быть заполнен'],
            ['login', 'match', 'exp' => '/^[a-zA-Z0-9\-\_]+$/',
                'message' => 'Логин содержит запрещенные символы.'],
            ['login', 'length', 'min' => 2, 'max' => 80,
                'message' => 'Логин не может превышать 80 символов и должен быть не меньше 2х символов.'],
            ['password', 'required', 'message' => 'Поле для пароля обязательное.'],
            ['retype-password', 'compare', 'cmp' => 'password',
                'message' => 'Пароли не совпадают.' ],
            ['phone', 'required', 'message' => 'Телефон должен быть заполнен.'],
            ['phone', 'phone', 'message' => 'Укажите правильно номер телефона.'],
            ['email', 'email', 'message' => 'Укажите email в парвильном формате.'],
            ['modules', 'buidModules'],
        ];

    }

    public function buidModules($field, $params = []) {

        if (!is_array($this->$field)) $this->$field = [];

        $modules = $this->$field;
        $modules = array_intersect_key(\Core\Users::get()->modules, $modules);


        foreach ($modules as $name => &$options) {

            if (isset($this->permitions[$name]))
                $options['permitions'] = $this->permitions[$name];

            else $options['permitions'] = [];

        }

        $this->$field = $modules;

    }

}
