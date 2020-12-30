<?php

namespace API\Interview\V1;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;

use Websm\Framework\Exceptions\HTTP as HTTPException;
use Websm\Framework\Exceptions\BaseException;

use Websm\Framework\Di;

class Controller extends Response
{

    public function getRoutes()
    {
        $group = Router::group();

        $group->addGet('/', function ($req, $next) {

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                $protocol = 'https';

            $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

            $routes = [
                'self' => Router::byName('api:interview:v1')
                    ->getAbsolutePath(),
                'quizs' => Router::byName('api:interview:v1:quizs')
                    ->getAbsolutePath(),
            ];

            $di = Di\Container::instance();

            $this->hal([
                '_links' => [
                    'self' => [
                        'href' => $origin . $routes['self'],
                    ],
                    'quizs' => [
                        'href' => $origin . $routes['quizs'],
                    ],
                ],
            ]);
        })->setName('api:interview:v1');

        $group->addGet('/quizs', [$this, 'getQuizs'])
              ->setName('api:interview:v1:quizs');

        $group->addGet('/quiz-:id', [$this, 'getQuiz'])
            ->setName('api:interview:v1:quiz');

        $group->addGet('/quiz-:id/questions', [$this, 'getQuestions'])
              ->setName('api:interview:v1:questions');

        $group->addGet('/quiz-:cid/question-:id', [$this, 'getQuestion'])
              ->setName('api:interview:v1:question');

        $group->addGet('/quiz-:cid/question-:id/answers', [$this, 'getAnswers'])
            ->setName('api:interview:v1:answers');

        $group->addGet('/answers/:id', [$this, 'getAnswer'])
              ->setName('api:interview:v1:answer');

        $group->addPost('/answers/:id', [$this, 'appendAnswer']);

        return $group;
    }

    public function getQuizs($req, $next)
    {
        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $order = Factory\QueryParams::getOrderQuestions();

        $qb = \Model\Interview\QuizModel::find(['visible' => true]);

        $qbCnt = clone $qb;

        $groups = $qb->order($order)
            ->limit([$offset, $limit])
            ->getAll();

        $total = (int) $qbCnt->count();

        $result = Factory\HAL\Quizs::get([
            'items' => $groups,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total,
        ]);

        $this->hal($result);
    }


    public function getQuestions($req, $next)
    {
        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $order = Factory\QueryParams::getOrderQuestions();

        $qb = \Model\Interview\Question::find(['visible' => true]);

        $qbCnt = clone $qb;

        $groups = $qb->order($order)
            ->limit([$offset, $limit])
            ->getAll();

        $total = (int) $qbCnt->count();

        $result = Factory\HAL\Questions::get([
            'items' => $groups,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total,
        ]);

        $this->hal($result);
    }

    public function getQuestion($req, $next)
    {
        try {

            $question = \Model\Interview\Question::find(['id' => $req['id']])
                ->andWhere(['visible' => true])
                ->get();

            if ($question->isNew())
                throw new HTTPException('question not found', 404);

            $result = Factory\HAL\Question::get([
                'item' => $question,
            ]);

            $this->hal($result);
        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    ['message' => $e->getMessage()],
                ]
            ]);
        }
    }

    public function getAnswers($req, $next)
    {
        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $order = Factory\QueryParams::getOrderAnswers();

        $qb = \Model\Interview\Answer::find(['question_id' => $req['id'], 'visible' => true]);

        $qbCnt = clone $qb;

        $answers = $qb->order($order)
            ->limit([$offset, $limit])
            ->getAll();

        $total = (int) $qbCnt->count();

        $result = Factory\HAL\Answers::get([
            'items' => $answers,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total,
        ]);

        $this->hal($result);
    }

    public function getAnswer($req, $next)
    {
        try {

            $answer = \Model\Interview\Answer::find(['id' => $req['id']])
                ->andWhere(['visible' => true])
                ->get();

            if ($answer->isNew())
                throw new HTTPException('answer not found', 404);

            $result = Factory\HAL\Answer::get([
                'item' => $answer,
            ]);

            $this->hal($result);
        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    ['message' => $e->getMessage()],
                ]
            ]);
        }
    }

    public function appendAnswer($req, $next)
    {
        try {

            $answer = \Model\Interview\Answer::find(['id' => $req['id']])
                ->andWhere(['visible' => true])
                ->get();

            if ($answer->isNew())
                throw new HTTPException('answer not found', 404);

            if(!$answer->inc())
                throw new HTTPException('unable to save answer');

            $result = Factory\HAL\Answer::get(['item' => $answer]);
            if (isset($this->params['appendQuestionPostHook']) && is_callable($this->params['appendQuestionPostHook'])) {

                call_user_func($this->params['appendQuestionPostHook'], $question);
            }

            $this->hal($result);

        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    ['message' => $e->getMessage()],
                ]
            ]);
        }
    }
}
