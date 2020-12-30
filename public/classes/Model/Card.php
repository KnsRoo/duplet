<?php

namespace Model;

use Websm\Framework\Db\ActiveRecord;

class Card extends ActiveRecord {

    public static $table = 'discount_card';

    public $id;
    public $code;
    public $sum;

    public function getRules() {
        return [
            ['id', 'pass'],
            ['code', 'pass'],
            ['sum', 'pass'],
        ];
    }
}
