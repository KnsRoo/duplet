<?php

namespace Model;

use Websm\Framework\Db\ActiveRecord;

class UserJWT extends ActiveRecord {

    public static $table = 'user_jwt';

    public $id;
    public $user_id;
    public $refresh_token;

    public function getRules(){
        return [
            ['id', 'pass'],
            ['user_id', 'pass'],
            ['refresh_token', 'pass'],
        ];
    }
}
