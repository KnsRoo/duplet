<?php

namespace Model;

use Websm\Framework\Db\ActiveRecord;

class Order extends ActiveRecord {

    public static $table = 'order';

    public $id;
    public $user_id = null;
    public $props = null;

    public function getRules() {

        return [
            ['user_id', 'native'],
            ['props', 'native'],
        ];
    }
}
