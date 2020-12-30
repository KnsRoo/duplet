<?php

namespace Back\Feedback;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Websm\Framework\Notify;
use Back\Layout\Layout;

use Core\Users;
use Core\ModuleInterface;
use Core\Module;

class Feedback extends Response implements ModuleInterface {

    protected $url = '/admin/feedback';
    protected $title = 'Обратная связь';

    public function __construct(&$props = []) { }

    public function init($req, $next) {

        $this->css = ['css/feedback.css'];
        $this->js = ['js/feedback.js'];

        Router::instance()
            ->mount('/', $this->getRoutes());

        $next();

    }

    public function getRoutes() {

        $router = Router::group();

        $api = new API\V1\Controller;
        $router->mount('/api/v1', $api->getRoutes());

        $router->addDelete('/delete-question-:id', [$this, 'deleteQuestion']);
        $router->addDelete('/ajax-delete-question-:id', [$this, 'deleteQuestionWithAjax']);

        $router->addDelete('/delete-answer-:id', [$this, 'deleteAnswer']);
        $router->addDelete('/ajax-delete-answer-:id', [$this, 'deleteAnswerWithAjax']); 

        $router->addPut('/show-question-:id', [$this, 'showQuestion']);
        $router->addPut('/ajax-show-question-:id',[$this, 'showQuestionWithAjax']);

        $router->addPut('/show-answer-:id', [$this, 'showAnswer']);
        $router->addPut('/ajax-show-answer-:id', [$this, 'showAnswerWithAjax']);

        $router->addPost('/send-answer-:id', [$this, 'sendAnswer']);
        $router->addPost('/ajax-send-answer-:id', [$this, 'sendAnswerWithAjax']);

        return $router;
    }

    public function deleteQuestionWithAjax($req) {

        $id = $req['id'];

        $ar = \Model\Feedback\Question::find()
            ->where(['id' => $id])
            ->get();

        if ($ar->delete()) {

            Notify::push('Сообщение удалено.', 'ok');

        } else {

            Notify::push('Ошибка.', 'err');

            foreach($ar->getErrors() as $error)
                Notify::pushArray($error);

        }

    }

    public function deleteAnswerWithAjax($req) {

        $id = $req['id'];

        $ar = \Model\Feedback\Answer::find()
            ->where(['id' => $id])
            ->get();

        if ($ar->delete()) {

            Notify::push('Сообщение удалено.', 'ok');

        } else {

            Notify::push('Ошибка.', 'err');

            foreach($ar->getErrors() as $error)
                Notify::pushArray($error);

        }

    }

    public function showQuestionWithAjax($req) {

        $id = $req['id'];

        $ar = \Model\Feedback\Question::find()
            ->where(['id' => $id])
            ->get();

        $ar->scenario('visibility');
        $ar->allowed = $ar->allowed ? 0 : 1;

        if ($ar->save())
            Notify::push('Изменена видимость.', 'ok');

        else {

            Notify::push('Ошибка.', 'err');

            foreach($ar->getErrors() as $error)
                Notify::pushArray($error);

        }

    }

    public function showAnswerWithAjax($req) {

        $id = $req['id'];

        $ar = \Model\Feedback\Answer::find()
            ->where(['id' => $id])
            ->get();

        $ar->scenario('visibility');
        $ar->allowed = $ar->allowed ? 0 : 1;

        if ($ar->save())
            Notify::push('Видимость изменена.', 'ok');

        else {

            Notify::push('Ошибка.', 'err');
            foreach($ar->getErrors() as $error)
                Notify::pushArray($error);

        }

    }

    public function sendAnswerWithAjax($req) {

        $ar = new \Model\Feedback\Answer('create');
        $ar->bind($_POST['answer']);
        $ar->id = md5(uniqid());
        $ar->question = $req['id'];
        $ar->user_name = Users::get()->login;

        if ($ar->save()) {

            Notify::push('Сообщение успешно отправлено.', 'ok');

        } else {

            Notify::push('Ошибка отправки сообщения.', 'err');

            foreach($ar->getErrors() as $error)
                Notify::pushArray($error);

        }

    }

    public function sendAnswer($req) {

        $ar = new \Model\Feedback\Answer();
        $ar->bind($_POST['answer']);
        $ar->id = md5(uniqid());
        $ar->question = $req['id'];
        $ar->user_name = Users::get()->login;

        if ($ar->save()) {

            Notify::push('Сообщение успешно отправлено.', 'ok');

        } else {

            Notify::push('Ошибка отправки сообщения.', 'err');

            foreach($ar->getErrors() as $error)
                Notify::pushArray($error);

        }

        $this->back();

    }

    public function showQuestion($req) {

        $id = $req['id'];

        $ar = \Model\Feedback\Question::find()
            ->where(['id' => $id])
            ->get();

        $ar->scenario('visibility');
        $ar->allowed = $ar->allowed ? 0 : 1;

        if ($ar->save()) {

            Notify::push('Сообщение показано.', 'ok');

        } else {

            Notify::push('Ошибка.', 'err');

            foreach($ar->getErrors() as $error)
                Notify::pushArray($error);

        }

        $this->back();

    }

    public function showAnswer($req) {

        $id = $req['id'];

        $ar = \Model\Feedback\Answer::find()
            ->where(['id' => $id])
            ->get();

        $ar->scenario('visibility');
        $ar->allowed = $ar->allowed ? 0 : 1;

        if ($ar->save()) {

            Notify::push('Сообщение показано.', 'ok');

        } else {

            Notify::push('Ошибка.', 'err');

            foreach($ar->getErrors() as $error)
                Notify::pushArray($error);

        }

        $this->back();

    }

    public function deleteQuestion($req) {

        $id = $req['id'];

        $ar = \Model\Feedback\Question::find()
            ->where(['id' => $id])
            ->get();

        if ($ar->delete()) {

            Notify::push('Сообщение удалено.', 'ok');

        } else {

            Notify::push('Ошибка.', 'err');

            foreach($ar->getErrors() as $error)
                Notify::pushArray($error);

        }

        $this->back();

    }

    public function deleteAnswer($req) {

        $id = $req['id'];

        $ar = \Model\Feedback\Answer::find()
            ->where(['id' => $id])
            ->get();

        if ($ar->delete()) {

            Notify::push('Сообщение удалено.', 'ok');

        } else {

            Notify::push('Ошибка.', 'err');

            foreach($ar->getErrors() as $error)
                Notify::pushArray($error);

        }

        $this->back();

    }


    public function getContent() {

        $data = [
            'questions' =>  \Model\Feedback\Question::find()
            ->order(['date DESC'])
            ->genAll(),
        ];

        return $this->render(__DIR__.'/temp/default.tpl', $data);

    }

    public function getSettings(){ } /* возможно нужно реализовать другой метод интерфейся */


        public function getSettingsContent($name = '', Array $permitions) {}

        public function setSettings(Array &$props = []) {}

}
