<?php

namespace Model;

use Websm\Framework\Db\ActiveRecord;

class Setting extends ActiveRecord {

    public static $table = 'site_setting';

    public $id;
    public $type = null;
    public $content = null;
    public $name;

    public function getRules() {

        return [
            [ 'id', 'pass' ],
            [ 'name', 'pass' ],
            [ 'type', 'pass' ],
            [ 'content', 'pass' ],
        ];
    }
}
