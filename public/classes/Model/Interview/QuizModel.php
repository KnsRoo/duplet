<?php

namespace Model\Interview;

use Websm\Framework\Db\ActiveRecord;
use DateTime;

class QuizModel extends ActiveRecord {

    public static $table = 'interview_quizs';

    public $id;
    public $title;
    public $sort = 1;
    public $date;

    protected function getRules() {
        return [

            ['id', 'required', 'on' => 'create'],
            ['title', ['required', 'striplace']],

            ['sort', 'int', 'on' => 'sort' , 'message' => 'Значение должно быть целым положительным числом'],
            ['sort', 'limit', 'on' => 'sort', 'min' => 0, 'message' => 'Значение должно быть целым положительным числом'],

            ['visible', 'int', 'on' => 'visibility'],
            ['visible', 'limit', 'on' => 'visibility', 'min' => 0, 'max' => 1],

            ['date', 'date', 'from' => 'd.m.Y', 'to' => 'Y-m-d', 'on' => 'update'],

        ];
    }

    public function getQuestions() {

        return Question::find(['quiz_id' => $this->id])
            ->order('sort')
            ->getAll();

    }

    public function getDate($format = 'd.m.Y') {

        return (new DateTime($this->date))
            ->format($format);

    }

    public function getFormatedDate($format = '%d.%m.%Y', $locale = null) {

        $current = setlocale(LC_TIME, null);
        setlocale(LC_TIME, $locale);

        $date = strftime($format, $this->getDate('U'));

        setlocale(LC_TIME, $current);

        return $date;

    }

    /**
     * isAvailable 
     *
     * Проверит доступность опроса для текущего ip
     * 
     * @access public
     * @return bool
     */
    public function isAvailable() {

        $ip = Journal::getIp();
        $result = Journal::find(['ip' => $ip])
            ->andWhere(['quiz_id' => $this->id])
            ->exists();

        return !$result;

    }

}
