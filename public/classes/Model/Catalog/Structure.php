<?php

namespace Model\Catalog;

use Websm\Framework\Db\ActiveRecord;

class Structure extends ActiveRecord
{

    public static $table = 'catalog_structure';

    public $id;
    public $cid = null;

    public function getRules()
    {
        return [
            ['id', 'pass'],
            ['cid', 'pass'],
        ];
    }
}
