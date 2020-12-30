<?php

namespace Model;

use Websm\Framework\Db\ActiveRecord;

class User extends ActiveRecord {

    public static $table = 'user';

    public $id;
    public $login;
    public $password;
    public $email;
    public $phone;
    public $creation_date;
    public $update_date;
    public $props;

    public function getRules(){
        return [
            ['id', 'pass'],
            ['email', 'pass'],
            ['phone', 'pass'],
            ['props', 'pass'],
        ];
    }
}
