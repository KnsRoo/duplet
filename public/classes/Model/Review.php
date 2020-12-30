<?php

namespace Model;

use Websm\Framework\Db\ActiveRecord;

class Review extends ActiveRecord {

    public static $table = 'catalog_product_review';

    public $id;
    public $product_id;
    public $user_id;
    public $rating;
    public $content;
    public $moderated;

    public function getRules(){
        return [
            ['id', 'pass'],
            ['product_id', 'pass'],
            ['user_id', 'pass'],
            ['rating', 'numeric', 'min' => 0, 'max' => 5],
            ['content', 'pass'],
            ['moderated', 'boolean'],
        ];
    }
}
