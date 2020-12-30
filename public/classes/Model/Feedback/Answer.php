<?php

namespace Model\Feedback;

use DateTime;

class Answer extends \Websm\Framework\Db\ActiveRecord {

    public static $table = 'feedback_answer';

    public $id;
    public $question_id;
    public $message;
    public $info;
    public $sort;
    public $visible;
    public $creation_date;
    public $update_date;

    protected function getRules() {

        return [
            ['question_id', 'pass'],
            ['message', 'pass'],
            ['info', 'pass'],
            ['sort', 'pass'],
            ['visible', 'boolean'],
        ];
    }
}
