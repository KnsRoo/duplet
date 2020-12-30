<?php

namespace Model\Feedback;

use Websm\Framework\Db\ActiveRecord;

class Question extends ActiveRecord {

    public static $table = 'feedback_question';

    public $id;
    public $user_id;
    public $info;
    public $sort;
    public $message;
    public $visible;
    public $creation_date;
    public $update_date;

    protected function getRules() {
        return [

            ['user_id', 'pass'],
            ['info', 'pass'],
            ['message', 'pass'],
            ['sort', 'pass'],
            ['visible', 'boolean'],
        ];
    }
}
