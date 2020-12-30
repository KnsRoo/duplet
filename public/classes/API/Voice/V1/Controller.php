<?php

namespace API\Voice\V1;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Websm\Voice\GoogleTextToSpeech\Client as GoogleClient;

use Websm\Framework\Exceptions\HTTP as HTTPException;

use Websm\Framework\Di\Container as Di;

class Controller extends Response {

    private $validationSchema = [
        'properties' => [
            'text' => [
                'type' => 'string',
                'pattern' => '^.{1,1000}$'
            ]
        ],
        'required' => 'text',
    ];

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', [ $this, 'showDefault' ])
              ->setName('api:voice:v1');

        $group->addPost('/generator', [$this, 'getVoice']);

        return $group;
    }

    public function showDefault($req, $next) {

        $this->hal([
            '_links' => [
                'self' => [
                    'href' => Router::byName("api:voice:v1")->getURL(),
                ],
            ],
            'validationSchema' => $this->validationSchema,
        ]);
    }

    public function getVoice($req, $next) {

        try {

            $rawBody = file_get_contents('php://input');
            $body = json_decode($rawBody, true);

            $schema = json_decode(json_encode($this->validationSchema));

            $validator = new \JsonSchema\Validator;
            $validator->validate($body, $schema);

            if (!$validator->isValid()) {

                $errors = $validator->getErrors();
                throw new HTTPException($errors[0]['message'], 422);
            }

            $text = $body['text'] ?? '';

            $client = (Di::instance())->get('voice');
            $content = $client->getMP3($text);

            header('Content-Disposition: inline;filename="voice.mp3"');
            header('Content-Type: audio/mpeg');
            die($content);

        } catch (HTTPException $e) {

            $this->code($e->getHttpCode());
            $this->json([
                'errors' => [
                    'message' => $e->getMessage(),
                ]
            ]);

        } catch (\Exception $e) {

            $this->code(500);
            $this->json([
                'errors' => [
                    'message' => $e->getMessage(),
                ]
            ]);
        }
    }
}
