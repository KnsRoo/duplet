<?php

namespace Back\Feedback\API\V1;

use Websm\Framework\Router\Router;
use Websm\Framework\Response;
use Websm\Framework\Sort;

use Model\Feedback\Question;
use Model\Feedback\Answer;

use Websm\Framework\Di;
use Websm\Framework\Exceptions\HTTP as HTTPException;

use Rs\Json\Patch;
use Rs\Json\Patch\InvalidPatchDocumentJsonException;
use Rs\Json\Patch\InvalidTargetDocumentJsonException;
use Rs\Json\Patch\InvalidOperationException;

use Core\Users;

class Controller extends Response {

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', function($req, $next) {

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                $protocol = 'https';

            $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

            $routes = [
                'self' => Router::byName('feedback:api:v1')
                    ->getAbsolutePath(),
                'questions' => Router::byName('feedback:api:v1:questions')
                    ->getAbsolutePath(),
            ];

            $this->hal([
                '_links' => [
                    'self' => [
                        'href' => $origin . $routes['self'],
                    ],
                    'questions' => [
                        'href' => $origin . $routes['questions'],
                    ],
                ],
            ]);
        })->setName('feedback:api:v1');

        $group->addGet('/questions', [$this, 'getQuestions'])
            ->setName('feedback:api:v1:questions');

        $group->addGet('/questions/:id', [$this, 'getQuestion'])
            ->setName('feedback:api:v1:question');

        $group->add('PATCH', '/questions/:id', [$this, 'updateQuestion'])
            ->setName('feedback:api:v1:question');

        $group->addDelete('/questions/:id', [$this, 'removeQuestion']);

        $group->addGet('/questions/:id/answers', [$this, 'getAnswers'])
            ->setName('feedback:api:v1:answers');

        $group->addPost('/questions/:id/answers', [$this, 'appendAnswer']);

        $group->add('PATCH', '/answers/:id', [$this, 'updateAnswer'])
            ->setName('feedback:api:v1:answer');

        $group->addDelete('/answers/:id', [$this, 'removeAnswer']);

        return $group;
    }

    public function getQuestions($req, $next) {

        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();

        $qb = \Model\Feedback\Question::find();
        $qbCnt = clone $qb;

        $order = Factory\QueryParams::getOrderQuestions();
        $questions = $qb->order($order)
            ->limit([ $offset, $limit ])
            ->getAll();

        $total = (Integer)$qbCnt->count();

        $result = Factory\HAL\Questions::get([
            'items' => $questions,
            'offset' => $offset,
            'limit' => $limit,
            'total' => $total,
        ]);

        $this->hal($result);
    }

    public function getQuestion($req, $next) {

        try {

            $question = \Model\Feedback\Question::find(['id' => $req['id']])
                ->get();

            if ($question->isNew())
                throw new HTTPException('question not found', 404);

            $result = Factory\HAL\Question::get([ 'item' => $question ]);
            $this->hal($result);

        } catch(HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ]
            ]);
        }
    }

    public function updateQuestion($req, $next) {

        try {

            $question = \Model\Feedback\Question::find(['id' => $req['id']])
                ->get();

            if ($question->isNew())
                throw new HTTPException('question not found', 404);

            $patchDoc = file_get_contents('php://input');

            $doc = json_encode([
                'userId' => $question->user_id,
                'info' => json_decode($question->info),
                'message' => $question->message,
                'sort' => $question->sort,
                'visible' => $question->visible,
            ]);

            $patchedDoc = '';

            try {
                $patch = new Patch($doc, $patchDoc);
                $patchedDoc = $patch->apply();
            } catch (InvalidPatchDocumentJsonException $e) {
                throw new HTTPException($e->getMessage(), 422);
            } catch (InvalidTargetDocumentJsonException $e) {
                throw new HTTPException($e->getMessage(), 500);
            } catch (InvalidOperationException $e) {
                throw new HTTPException($e->getMessage(), 422);
            }

            $questionObj = json_decode($patchedDoc);
            if (!property_exists($questionObj, 'userId'))
                throw new HTTPException('unable patch document', 422);

            if (!property_exists($questionObj, 'info'))
                throw new HTTPException('unable patch document', 422);

            if (!property_exists($questionObj, 'message'))
                throw new HTTPException('unable patch document', 422);

            if (!property_exists($questionObj, 'sort'))
                throw new HTTPException('unable patch document', 422);

            if (!property_exists($questionObj, 'visible'))
                throw new HTTPException('unable patch document', 422);

            $question->user_id = $questionObj->userId;
            $question->info = json_encode($questionObj->info);
            $question->message = $questionObj->message;
            $question->sort = $questionObj->sort;
            $question->visible = $questionObj->visible;

            if (!$question->save())
                throw new HTTPException('unable to save question', 500);

            $result = Factory\HAL\Question::get([
                'item' => $question,
            ]);

            $this->hal($result);

        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } 
    }

    public function removeQuestion($req, $next) {

        try {

            $question = \Model\Feedback\Question::find(['id' => $req['id']])
                ->get();

            if ($question->isNew())
                throw new HTTPException('question not found', 404);

            $question->delete();
            $this->code(204);
            die();

        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } 
    }

    public function getAnswers($req, $next) {

        try {

            $offset = Factory\QueryParams::getOffset();
            $limit = Factory\QueryParams::getLimit();

            $qb = \Model\Feedback\Answer::find(['question_id' => $req['id']]);
            $qbCnt = clone $qb;

            $order = Factory\QueryParams::getOrderAnswers();
            $answers = $qb->order($order)
                ->limit([ $offset, $limit ])
                ->getAll();

            $total = (Integer)$qbCnt->count();

            $result = Factory\HAL\Answers::get([
                'items' => $answers,
                'offset' => $offset,
                'limit' => $limit,
                'total' => $total,
            ]);

            $this->hal($result);

        } catch(HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ]
            ]);
        }
    }

    public function getAnswer($req, $next) {

        try {

            $answer = \Model\Feedback\Answer::find(['id' => $req['id']])
                ->get();

            if ($answer->isNew())
                throw new HTTPException('answer not found', 404);

            $result = Factory\HAL\Answer::get([ 'item' => $answer ]);
            $this->hal($result);

        } catch(HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    [ 'message' => $e->getMessage() ],
                ]
            ]);
        }
    }

    public function appendAnswer($req, $next) {

        try {

            $question = \Model\Feedback\Question::find(['id' => $req['id']])
                ->get();

            if ($question->isNew())
                throw new HTTPException('question not found', 404);

            $body = json_decode(file_get_contents('php://input'));

            if (!property_exists($body, 'message'))
                throw new HTTPException('invalid request', 422);
            $message = $body->message;

            $info = (Object)null;
            if (property_exists($body, 'info'))
                $info = (Object)$body->info;

            $info->user = (Object)[
                'type' => 'string',
                'value' => Users::get()->login,
            ];

            $answer = new \Model\Feedback\Answer;
            $answer->question_id = $question->id;
            $answer->message = $message;
            $answer->info = json_encode($info);
            $answer->visible = 1;

            if (!$answer->save()) {
                throw new HTTPException('unable to save answer', 500);
            }
            $answerId = \Model\Feedback\Answer::getDb()
                ->lastInsertId();

            $answer = \Model\Feedback\Answer::find([ 'id' => $answerId ])
                ->get();

            $sort = Sort::init($answer);
            $sort->normalise();

            $result = Factory\HAL\Answer::get([
                'item' => $answer,
            ]);

            $this->hal($result);

        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } 
    }

    public function updateAnswer($req, $next) {

        try {

            $answer = \Model\Feedback\Answer::find(['id' => $req['id']])
                ->get();

            if ($answer->isNew())
                throw new HTTPException('answer not found', 404);

            $patchDoc = file_get_contents('php://input');

            $doc = json_encode([
                'questionId' => $answer->question_id,
                'info' => json_decode($answer->info),
                'message' => $answer->message,
                'sort' => $answer->sort,
                'visible' => $answer->visible,
            ]);

            $patchedDoc = '';

            try {
                $patch = new Patch($doc, $patchDoc);
                $patchedDoc = $patch->apply();
            } catch (InvalidPatchDocumentJsonException $e) {
                throw new HTTPException($e->getMessage(), 422);
            } catch (InvalidTargetDocumentJsonException $e) {
                throw new HTTPException($e->getMessage(), 500);
            } catch (InvalidOperationException $e) {
                throw new HTTPException($e->getMessage(), 422);
            }

            $answerObj = json_decode($patchedDoc);
            if (!property_exists($answerObj, 'questionId'))
                throw new HTTPException('unable patch document', 422);

            if (!property_exists($answerObj, 'info'))
                throw new HTTPException('unable patch document', 422);

            if (!property_exists($answerObj, 'message'))
                throw new HTTPException('unable patch document', 422);

            if (!property_exists($answerObj, 'sort'))
                throw new HTTPException('unable patch document', 422);

            if (!property_exists($answerObj, 'visible'))
                throw new HTTPException('unable patch document', 422);

            $answer->question_id = $answerObj->questionId;
            $answer->info = json_encode($answerObj->info);
            $answer->message = $answerObj->message;
            $answer->sort = $answerObj->sort;
            $answer->visible = $answerObj->visible;

            if (!$answer->save())
                throw new HTTPException('unable to save answer', 500);

            $result = Factory\HAL\Answer::get([
                'item' => $answer,
            ]);

            $this->hal($result);

        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } 
    }

    public function removeAnswer($req, $next) {

        try {

            $answer = \Model\Feedback\Answer::find(['id' => $req['id']])
                ->get();

            if ($answer->isNew())
                throw new HTTPException('answer not found', 404);

            $answer->delete();
            $this->code(204);
            die();

        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'messages' => $e->getMessage(),
                ],
            ]);
        } 
    }
}
