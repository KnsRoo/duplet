<?php

namespace Back\Mailing\Models;

use Core\Db\ActiveRecord;

class Subscriber extends ActiveRecord {

    public static $table = 'subscriber';

    public $id;
    public $email;

    public function getRules() {

        return [
            ['id', 'required', 'on' => 'create'],
            ['email', 'required'],
            ['email', 'email'],
        ];

    }

}
