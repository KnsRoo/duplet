<?php

namespace API\Orders\V1\Factory\Filters;
use Websm\Framework\Exceptions\HTTP as HTTPException;
use Websm\Framework\Di;

class Body {

    const TYPES = [
        'ФИО' => 'string',
        'Телефоны' => 'array of strings',
        'Электронные почты' => 'array of strings',
        'Способ получения' => 'string',
        'Способ оплаты' => 'string',
        null => 'json',
    ];

    public static function filterProps($content, $params) {

        $validationScheme = $params['validationScheme'];

        $schema = json_decode(json_encode($validationScheme));
        $content = json_decode($content);

        $validator = new \JsonSchema\Validator;
        $validator->validate($content, $schema);

        if (!$validator->isValid()) {

            $errors = $validator->getErrors();
            throw new HTTPException($errors[0]['message'], 422);
        }

        $result = [];

        foreach($content as $key => $value) {

            $type = 'json';

            if (isset(self::TYPES[$key]))
                $type = self::TYPES[$key];

            $result[$key] = [
                'type' => $type,
                'value' => $value,
            ];
        }

        if(isset($result['ФИО']))
        {
            $fio = explode(' ', $result['ФИО']['value']);
            unset($result['ФИО']);

            $result['Фамилия'] = [
                'type' => 'string',
                'value' => $fio[0],
            ];

            $result['Имя'] = [
                'type' => 'string',
                'value' => $fio[1] ?? '',
            ];

            $result['Отчество'] = [
                'type' => 'string',
                'value' => $fio[2] ?? '',
            ];
        }

        $dateTime = new \DateTime('now', new \DateTimeZone('Europe/Moscow'));
        $date = $dateTime->format('Y-m-d H:i:s');

        $result['Дата'] = [
            'type' => 'string',
            'value' => $date,
        ];

        $result['Код'] = [
            'type' => 'string',
            'value' => uniqid(),
        ]; 

        $di = Di\Container::instance();
        $cart = $di->get('cart');

        $result['Сумма'] = [
            'type' => 'number',
            'value' => $cart->getSumm(),
        ];

        if ($cart->isEmpty())
            throw new HTTPException('unable to submit empty cart', 400);

        $cartItems = $cart->getItems();

        $items = [];

        foreach($cartItems as $item)
            $items[] = $item->asArray();

        $result['Товары'] = [
            'type' => 'cart items',
            'value' => $items,
        ];

        return json_encode($result);
    }
}
