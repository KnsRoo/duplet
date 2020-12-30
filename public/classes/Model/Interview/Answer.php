<?php

namespace Model\Interview;

use Websm\Framework\Db\ActiveRecord;
use DateTime;

class Answer extends ActiveRecord {

    public static $table = 'interview_answers';

    public $id;
    public $question_id;
    public $text;
    public $count = 0;
    public $date;
    public $sort = 1;

    protected function getRules() {

        return [

            ['id', 'required', 'on' => 'create'],
            ['question_id', 'required' ],

            ['count', 'int', 'on' => 'increment'],

            ['text', ['striplace', 'required'] ],

            ['sort', 'int', 'on' => 'sort',
            'message' => 'Значение должно быть целым положительным числом'],
            ['sort', 'limit', 'on' => 'sort', 'min' => 0,
            'message' => 'Значение должно быть целым положительным числом'],

            ['visible', 'int', 'on' => 'visibility'],
            ['visible', 'limit', 'on' => 'visibility', 'min' => 0, 'max' => 1],

        ];

    }

    public function getQuiz() {

        $question = $this->getQuestion();

        $quiz = QuizModel::find(['id' => $question->quiz_id])
            ->get();

        return $quiz;

    }

    public function getQuestion() {

        $question = Question::find(['id' => $this->question_id])
            ->get();

        return $question;

    }

    public function getStatistic() {

        $question = Question::find(['question_id' => $this->id])
            ->get();

        $answersSum = $question->getAnswersSum();

        $answerStatistic = ($this->count / $answersSum) * 100;

        return $answerStatistic;

    }

    public function getDate($format = 'd.m.Y') {

        return (new DateTime($this->date))
            ->format($format);

    }

    public function inc() {

        $journal = new Journal;

        $quiz = $this->getQuiz();

        $journal->quiz_id = $quiz->id;

        if (!$journal->save())
            return false;

        $this->scenario('increment');
        $this->count++;

        if (!$this->save())
            return false;

        return true;

    }

    public function getPercentOf($num) {

        $percent = $num / 100;
        return $percent ? ($this->count / $percent) : 0;

    }

}
