<?php

namespace Back\Interview;

use Core\Module;
use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Back\Layout\Layout;
use Websm\Framework\Notify;
use Core\ModuleInterface;
use Websm\Framework\Sort;

use Model\Interview\QuizModel as Quiz;
use Model\Interview\Question;
use Model\Interview\Answer;

class Interview extends Response implements ModuleInterface {

    public $permitions = [
        'creating' => 'off',
        'deleting' => 'off',
        'editing' => 'off',
        'chroot' => null,
    ];

    protected $url = '/admin/interview';
    protected $title = 'Опросы';

    private $activeQuiz;
    private $activeQuestion;

    public function __construct(&$props = []) { }

    public function init($req, $next) {

        Router::instance()
            ->mount('/', $this->getRoutes());

        $next();

    }

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', [$this, 'getQuizs'])
            ->setName('Interview.getQuizs');

        $group->addGet('/quiz-:id', [$this, 'getQuestions'])
              ->setName('Interview.getQuestions');

        $group->addGet('/quiz-:cid/question-:id', [$this, 'getAnswers'])
            ->setName('Interview.getAnswers');

        $group->addPost('/create-quiz', [$this, 'createQuiz'])
            ->setName('Interview.createQuiz');

        $group->addPost('/quiz-:id/create-question', [$this, 'createQuestion'])
              ->setName('Interview.createQuestion');

        $group->addPost('/quiz-:cid/question-:id/create-answer', [$this, 'createAnswer'])
            ->setName('Interview.createAnswer');

        $group->addDelete('/delete-quiz-:id', [$this, 'deleteQuiz'])
            ->setName('Interview.deleteQuiz');

        $group->addDelete('/delete-question-:id', [$this, 'deleteQuestion'])
              ->setName('Interview.deleteQuestion');

        $group->addDelete('/delete-answer-:id', [$this, 'deleteAnswer'])
            ->setName('Interview.deleteAnswer');

        $group->addPut('/update-visibility-quiz-:id', [$this, 'updateVisQuiz'])
            ->setName('Interview.updateVisQuiz');

        $group->addPut('/update-visibility-question-:id', [$this, 'updateVisQuestion'])
              ->setName('Interview.updateVisQuestion');

        $group->addPut('/update-visibility-answer-:id', [$this, 'updateVisAnswer'])
              ->setName('Interview.updateVisAnswer');

        $group->addPut('/quiz-:id/update-sort', [$this, 'updateQuizSort'])
            ->setName('Interview.updateQuizSort');

        $group->addPut('/question-:id/update-sort', [$this, 'updateQuestionSort'])
            ->setName('Interview.updateQuestionSort');

        $group->addPut('/answer-:id/update-sort', [$this, 'updateAnswerSort'])
            ->setName('Interview.updateAnswerSort');

        return $group;

    }

    public function getQuizs($req)
    {
        $quizs = Quiz::find()
            ->order(['sort'])
            ->genAll();

        $html = '';

        foreach ($quizs as $quiz) {
            $data = [
                'quiz' => $quiz,
            ];

            $html .= $this->render(__DIR__.'/temp/quiz.tpl', $data);
        }

        $this->content = $html;

    }

    public function getQuestions($req) {

        $quiz = Quiz::find(['id' => $req['id']])
            ->get();

        $questions = $quiz->getQuestions();

        $this->activeQuiz = $quiz;

        $html = '';

        foreach ($questions as $question) {
            $data = [
                'question' => $question,
            ];

            $html .= $this->render(__DIR__.'/temp/question.tpl', $data);
        }

        $this->content = $html;

    }

    public function getAnswers($req) {

        $quiz = Quiz::find(['id' => $req['cid']])
            ->get();

        $question = Question::find(['id' => $req['id']])
            ->get();

        $totalVotes = $question->getTotalVotes();
        $answers = $question->getAnswers();

        $this->activeQuiz = $quiz;
        $this->activeQuestion = $question;

        $html = '';

        foreach ($answers as $answer) {
            $data = [];
            $data = [
                'answer' => $answer,
                'percent' => $answer->getPercentOf($totalVotes),
            ];

            $html .= $this->render(__DIR__.'/temp/answer.tpl', $data);
        }

        $this->content = $html;

    }

    public function createQuiz($req) {

        $ar = new Quiz('create');
        $ar->bind($_POST['create']);
        $ar->id = md5(uniqid());

        if ($ar->save()) {

            $sort = Sort::init($ar, 'interview_quizs');
            $sort->normalise();
            Notify::push('Опрос успешно создан.', 'ok');

        } else {

            Notify::push('Ошибка создания опроса.', 'err');

            foreach ($ar->getErrors() as $error)
                Notify::pushArray($error);

        }

        $this->back();

    }

    public function createQuestion($req)
    {
        $ar = new Question('create');
        $ar->bind($_POST['create']);
        $ar->id = md5(uniqid());
        $ar->quiz_id = $req['id'];

        if ($ar->save()) {

            $sort = Sort::init($ar, 'interview_questions');
            $sort->normalise();
            Notify::push('Вопрос успешно создан.', 'ok');

        } else {

            Notify::push('Ошибка создания вопроса.', 'err');

            foreach ($ar->getErrors() as $error)
                Notify::pushArray($error);

        }

        $this->back();
    }

    public function createAnswer($req) {

        $ar = new Answer('create');
        $ar->bind($_POST['create']);
        $ar->id = md5(uniqid());
        $ar->question_id = $req['id'];

        if ($ar->save()) {

            $sort = Sort::init($ar, 'interview_answers');
            $sort->normalise();
            Notify::push('Ответ успешно создан.', 'ok');

        } else {

            Notify::push('Ошибка создания ответа.', 'err');

            foreach ($ar->getErrors() as $error)
                Notify::pushArray($error);

        }

        $this->back();

    }

    public function deleteQuiz($req) {

        $quiz = Quiz::find(['id' => $req['id']])->get();

        $questions = Question::find(['quiz_id' => $quiz->id])
            ->getAll();

        if ($quiz->delete()) {

            foreach ($questions as $question) {
                $answers = Answer::find(['question_id' => $question->id])
            ->getAll();

                if (!$question->delete()) {
                    Notify::push('Ошибка.', 'err');

                    foreach ($answer->getErrors() as $error)
                        Notify::pushArray($error);
                }

                foreach ($answers as $answer) {
                if (!$answer->delete()) {
                    Notify::push('Ошибка.', 'err');

                    foreach ($answer->getErrors() as $error)
                        Notify::pushArray($error);
                    }
                }

            }

            $sort = Sort::init($quiz, 'interview_quizs');
            $sort->normalise();
            Notify::push('Опрос удалён.', 'ok');

        } else {

            Notify::push('Ошибка.', 'err');

            foreach ($question->getErrors() as $error)
                Notify::pushArray($error);

        }

        $this->back();

    }

    public function deleteQuestion($req) {

        $question = Question::find(['id' => $req['id']])->get();

        $answers = Answer::find(['question_id' => $question->id])
            ->getAll();

        if ($question->delete()) {

            foreach ($answers as $answer) {
                if (!$answer->delete()) {
                    Notify::push('Ошибка.', 'err');

                    foreach ($answer->getErrors() as $error)
                        Notify::pushArray($error);
                }
            }

            $sort = Sort::init($question, 'interview_questions');
            $sort->normalise();
            Notify::push('Вопрос удалён.', 'ok');

        } else {

            Notify::push('Ошибка.', 'err');

            foreach ($question->getErrors() as $error)
                Notify::pushArray($error);

        }

        $this->back();

    }


    public function deleteAnswer($req) {

        $ar = Answer::find(['id' => $req['id']])->get();

        if ($ar->delete()) {

            $sort = Sort::init($ar, 'interview_answers');
            $sort->normalise();
            Notify::push('Ответ удален.', 'ok');

        } else {

            Notify::push('Ошибка.', 'err');

            foreach ($ar->getErrors() as $error)
                Notify::pushArray($error);

        }

        $this->back();

    }

    public function updateVisQuiz($req) {

        $ar = Quiz::find(['id' => $req['id']])->get();
        $ar->scenario('visibility');
        $ar->visible = $ar->visible ? 0 : 1;

        if($ar->save()) {

            Notify::push('Видимость опроса успешно изменена', 'ok');

        } else {

            Notify::push('Ошибка.', 'err');

            foreach ($ar->getErrors() as $error)
                Notify::pushArray($error);

        }

        $this->back();

    }

    public function updateVisQuestion($req) {

        $ar = Question::find(['id' => $req['id']])->get();
        $ar->scenario('visibility');
        $ar->visible = $ar->visible ? 0 : 1;

        if($ar->save()) {

            Notify::push('Видимость вопроса успешно изменена', 'ok');

        } else {

            Notify::push('Ошибка.', 'err');

            foreach ($ar->getErrors() as $error)
                Notify::pushArray($error);

        }

        $this->back();

    }


    public function updateVisAnswer($req) {

        $ar = Answer::find(['id' => $req['id']])->get();
        $ar->scenario('visibility');
        $ar->visible = $ar->visible ? 0 : 1;

        if ($ar->save()) {

            Notify::push('Видимость ответа успешно изменена', 'ok');

        } else {

            Notify::push('Ошибка.', 'err');

            foreach ($ar->getErrors() as $error)
                Notify::pushArray($error);

        }

        $this->back();

    }

    public function updateQuizSort($req) {

        $quiz = Quiz::find(['id' => $req['id']])
            ->get();

        if ($quiz->isNew()) {

            Notify::push('Опрос не существует', 'err');
            $this->back();

        }

        $sort = Sort::init($quiz, '');
        $sort->move((int)$_POST['sort']);
        $sort->normalise();

        $this->back();

    }

    public function updateQuestionSort($req) {

        $question = Question::find(['id' => $req['id']])
            ->get();

        if ($question->isNew()) {

            Notify::push('Вопрос не существует', 'err');
            $this->back();

        }

        $sort = Sort::init($question, '');
        $sort->move((int)$_POST['sort']);
        $sort->normalise();

        $this->back();

    }

    public function updateAnswerSort($req) {

        $answer = Answer::find(['id' => $req['id']])
            ->get();

        if ($answer->isNew()) {

            Notify::push('Ответ не существует', 'err');
            $this->back();

        }

        $sort = Sort::init($answer, 'question_id');
        $sort->move((int)$_POST['sort']);
        $sort->normalise();

        $this->back();

    }

    public function getContent() {

        $data = [];
        $data['quiz'] = $this->activeQuiz;
        $data['question'] = $this->activeQuestion;
        return $this->render(__DIR__.'/temp/default.tpl', $data);

    }

    public function setSettings(Array &$props = []) 
    {
        $this->permitions = array_merge($this->permitions, $props);
        $this->permitions['chroot'] = $this->permitions['chroot'] ?: null;
    }

    public function getSettings() 
    { 
        return $this->permitions;
    }

    public function getSettingsContent($name = '', Array $permitions) 
    {
        return $this->render(__DIR__ . '/temp/settings.tpl', $permitions);
    }

}
