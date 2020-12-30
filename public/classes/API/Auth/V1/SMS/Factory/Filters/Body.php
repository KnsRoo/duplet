<?php

namespace API\Auth\V1\SMS\Factory\Filters;

use Websm\Framework\Exceptions\HTTP as HTTPException;
use Websm\Framework\Di;
use Websm\Framework\GreCAPTCHA;

class Body {

    const TYPES = [
        'name' => 'string',
        'surname' => 'string',
        'patronymic' => 'string',
        'phones' => 'array of strings',
        'emails' => 'array of strings',
        null => 'json',
    ];

    public static function filterInfo($content, $schema) {

        $schema = json_decode(json_encode($schema));
        $content = json_decode($content);

        $validator = new \JsonSchema\Validator;
        $validator->validate($content, $schema);

        if (!$validator->isValid()) {
            $errors = $validator->getErrors();
            throw new HTTPException($errors[0]['message'], 422);
        }

        $result = [];

        $types = self::TYPES;
        unset($content->i_agree);
        unset($content->grecaptchaToken);
        $result = $content;

        return json_encode($result);
    }
}
