<?php

namespace Model\Catalog;

use Websm\Framework\Db\ActiveRecord;

class Favorite extends ActiveRecord
{

    public static $table = 'catalog_favorites';

    public $id;
    public $user_id;
    public $product_id;

    public function getRules()
    {
        return [
            ['id', 'pass'],
            ['user_id', 'pass'],
            ['product_id', 'pass'],
        ];
    }
}
