<?php

namespace API\Feedback\V1;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;

use Websm\Framework\Exceptions\HTTP as HTTPException;
use Websm\Framework\Exceptions\BaseException;
use Websm\Framework\Mail\HTMLMessage;

use Components\SettingWidget\Widget as Setting;

use Websm\Framework\Di;

class Controller extends Response
{

    private $validationSchema;
    private $verifyRecaptcha = true;
    
    public function __construct($params = [])
    {
        $this->validationSchema = $params['validationSchema'];
        $this->verifyRecaptcha = isset($params['verifyRecaptcha']) ? $params['verifyRecaptcha'] : $this->verifyRecaptcha;
        $this->params = $params;
    }

    public function getRoutes()
    {
        $group = Router::group();

        $group->addGet('/', function ($req, $next) {

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                $protocol = 'https';

            $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

            $routes = [
                'self' => Router::byName('api:feedback:v1')
                    ->getAbsolutePath(),
                'questions' => Router::byName('api:feedback:v1:questions')
                    ->getAbsolutePath(),
            ];

            $di = Di\Container::instance();
            $grecaptcha = $di->get('grecaptcha');
            $siteKey = $grecaptcha->getSiteKey();

            $this->hal([
                'siteKey' => $siteKey,
                'validationSchema' => $this->validationSchema,
                '_links' => [
                    'self' => [
                        'href' => $origin . $routes['self'],
                    ],
                    'questions' => [
                        'href' => $origin . $routes['questions'],
                    ],
                ],
            ]);
        })->setName('api:feedback:v1');

        $group->addGet('/questions', [$this, 'getQuestions'])
            ->setName('api:feedback:v1:questions');

        $group->addPost('/questions', [$this, 'appendQuestion']);

        $group->addGet('/questions/:id', [$this, 'getQuestion'])
            ->setName('api:feedback:v1:question');

        $group->addGet('/questions/:id/answers', [$this, 'getAnswers'])
            ->setName('api:feedback:v1:answers');

        $group->addGet('/answers/:id', [$this, 'getAnswer'])
            ->setName('api:feedback:v1:answer');

        return $group;
    }

    public function getQuestions($req, $next)
    {
        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $order = Factory\QueryParams::getOrderQuestions();

        $qb = \Model\Feedback\Question::find(['visible' => true]);

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

            $question = \Model\Feedback\Question::find(['id' => $req['id']])
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

    public function appendQuestion($req, $next)
    {
        try {

            $body = file_get_contents('php://input');
            $bodyObj = json_decode($body);

            $token = '';
            if (key_exists('grecaptchaToken', $bodyObj))
                $token = $bodyObj->grecaptchaToken;

            $body = Factory\Filters\Body::filterInfo($body, $this->validationSchema);
            $body = json_decode($body, true);

            $token = $bodyObj->grecaptchaToken;
            $di = Di\Container::instance();
            $grecaptcha = $di->get('grecaptcha');

            if ($this->verifyRecaptcha && !$grecaptcha->verify($token))
                throw new HTTPException('captcha vierification failed', 422);

            $question = new \Model\Feedback\Question;
            $question->user_id = null; // user should be verified via Authorize: token header
            $question->sort = 0;
            $question->message = $body['message']['value'];
            unset($body['message']);
            $question->info = json_encode((object) $body);

            if (!$question->save())
                throw new BaseException('unable to save question');

            $questionId = \Model\Feedback\Question::getDb()
                ->lastInsertId();

            $question = \Model\Feedback\Question::find(['id' => $questionId])
                ->get();

            /*$this->sendAdminMail($question);*/

            $result = Factory\HAL\Question::get(['item' => $question]);
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

    private function sendAdminMail($question)
    {
        $questionInfo = json_decode($question->info, true);

        $emails = Setting::get('Emails');

        $domain = $_SERVER['SERVER_NAME'];

        $data = [
            'userName' => $questionInfo['name']['value'],
            'userPhone' => $questionInfo['phones']['value'][0],
            'userEmail' => $questionInfo['emails']['value'][0],
            'questionMessage' => $question->message,
        ];

        $body = $this->render(__DIR__ . '/temp/email.tpl', $data);

        foreach ($emails as $email) {
            $mail = new HTMLMessage();
            $mail->setFrom('<noreply@websm.io>')
                ->setTo($email)
                ->setSubject('Новое обращение на сайте ' . $domain)
                ->setBody($body);

            $mail->send();
        }
    }

    public function getAnswers($req, $next)
    {
        $offset = Factory\QueryParams::getOffset();
        $limit = Factory\QueryParams::getLimit();
        $order = Factory\QueryParams::getOrderAnswers();

        $qb = \Model\Feedback\Answer::find(['question_id' => $req['id'], 'visible' => true]);

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

            $answer = \Model\Feedback\Answer::find(['id' => $req['id']])
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
    public function getRelDoc($req, $next)
    {
        $validRels = ['questions', 'question', 'answers', 'answer'];
        $validAccepts = ['text/html', 'text/yaml'];

        $headers = apache_request_headers();
        $accepts = &$headers['Accept'];
        $accepts = (string) $accepts;
        $accepts = explode(',', $accepts);
        $accept = 'text/yaml';

        foreach ($accepts as $acceptItem)
            if (in_array($acceptItem, $validAccepts)) {
                $accept = $acceptItem;
                break;
            }

        $rel = $req['rel'];
        if (!in_array($rel, $validRels)) {
            $this->code(404);
            die();
        }

        switch ($accept) {
            case 'text/html':
                header('Content-Type: text/html');
                die(file_get_contents(__DIR__ . "/doc/${rel}.html"));
                break;
            case 'text/yaml':
                header('Content-Type: text/yaml');
                die(file_get_contents(__DIR__ . "/doc/${rel}.yml"));
                break;
        }
    }
}
