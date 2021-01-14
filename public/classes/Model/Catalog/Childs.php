<?php

namespace Model\Catalog;

use Websm\Framework\Db\ActiveRecord;

class Childs extends ActiveRecord
{

    public static $table = 'catalog_childs';

    public $id;
    public $childs;

    public function getRules()
    {
        return [
            ['id', 'pass'],
            ['childs', 'pass'],
        ];
    }
}
