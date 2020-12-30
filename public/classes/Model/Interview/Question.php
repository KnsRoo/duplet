<?php

namespace Model\Interview;

use Websm\Framework\Db\ActiveRecord;
use DateTime;

class Question extends ActiveRecord {

    public static $table = 'interview_questions';

    public $id;
    public $quiz_id;
    public $title;
    public $sort = 1;
    public $date;

    protected function getRules() {
        return [

            ['id', 'required', 'on' => 'create'],
            ['quiz_id', 'required' ],
            ['title', ['required', 'striplace']],

            ['sort', 'int', 'on' => 'sort' , 'message' => 'Значение должно быть целым положительным числом'],
            ['sort', 'limit', 'on' => 'sort', 'min' => 0, 'message' => 'Значение должно быть целым положительным числом'],

            ['visible', 'int', 'on' => 'visibility'],
            ['visible', 'limit', 'on' => 'visibility', 'min' => 0, 'max' => 1],

            ['date', 'date', 'from' => 'd.m.Y', 'to' => 'Y-m-d', 'on' => 'update'],

        ];
    }

    public function getAnswers() {

        return Answer::find(['question_id' => $this->id])
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

    public function getAnswersStatistic() {

        $answers = Answer::find(['question_id' => $this->id])
            ->getAll();


        $answersSum = 0;

        foreach ($answers as $answer) {

            $answersSum += $answer->count;

        }

        $answersStatistic = [];

        foreach ($answers as $answer) {

            if ($answersSum == 0) $answersStatistic[$answer->id] = 0;
            else {

                $answersStatistic[$answer->id] = round(($answer->count / $answersSum) * 100);

            }

        }

        return $answersStatistic;

    }

    /**
     * getTotalVotes 
     *
     * Получит общее количество голосов.
     * 
     * @access public
     * @return int
     */
    public function getTotalVotes () {

        $result = Answer::query()
            ->select('SUM(`count`) as `total`')
            ->where(['question_id' => $this->id])
            ->get();

        if ($result) return $result['total'];
        else return 0;

    }
}
