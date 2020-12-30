<?php

namespace Back\Auth;

use Websm\Framework\Db\ActiveRecord;

class ForBan extends ActiveRecord {

    public static $table = 'forBan';

    public $ip = '';
    public $banTimeStart = null;
    public $banTimeEnd = null;
    public $trys = 3;

    public function getRules() {

        return [
            ['ip', 'required'],
            ['banTimeStart', 'pass'],
            ['banTimeEnd', 'pass'],
            ['trys', 'native'],
            ['trys', 'required'],
        ];

    }

}
