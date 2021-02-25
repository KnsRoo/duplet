<?php

namespace API\Orders\V1\Factory\Filters;
use Websm\Framework\Exceptions\HTTP as HTTPException;
use Websm\Framework\Di;

use Model\Catalog\Group;
use Model\Catalog\Childs;

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

        $di = Di\Container::instance();
        $cart = $di->get('cart');

        if ($cart->isEmpty())
            throw new HTTPException('unable to submit empty cart', 400);

        $cartItems = $cart->getItems();

        $items = [];
        $reserved = [];

        $specialArray = [];
        $speacialGroups = Group::byTags(['Бронирование'])
            ->getAll();

        foreach ($speacialGroups as $group) {
            $childs = Childs::find(['id' => $group->id])->get();
            $arr = $childs->getChildArray();
            $specialArray = array_merge($specialArray,$arr);
        }

        foreach($cartItems as $item){

            $temp = $item->asArray();
            $temp['status'] = (in_array($item->product->cid,$specialArray)) ? 'reserved' : 'ready';
            if ($temp['status'] == 'ready'){
                $items[] = $temp;
            } else if ($temp['status'] == 'reserved') {
                $reserved[] = $temp;
            }
            
        }

        var_dump($items);
        var_dump($reserved);

        $items_summ = 0;
        $reserved_summ = 0;
        
        foreach ($items as $item) {
            if ($item['discount_price']){
                $items_summ += (int)$item['count'] * (float)$item['discount_price'];
            } else {
                $items_summ += (int)$item['count'] * (float)$item['price'];
            }
        }

        foreach ($reserved as $item) {
            if ($item['discount_price']){
                $reserved_summ += (int)$item['count'] * (float)$item['discount_price'];
            } else {
                $reserved_summ += (int)$item['count'] * (float)$item['price'];
            }
        }

        $result['Товары'] = [
            'type' => 'cart items',
            'value' => $items,
        ];

        $result['Сумма'] = [
            'type' => 'number',
            'value' => $items_summ,
        ];

        $result['Код'] = [
            'type' => 'string',
            'value' => uniqid(),
        ]; 

        if ($reserved){
            $result_reserved = $result;

            $result_reserved['Сумма'] = [
                'type' => 'number',
                'value' => $reserved_summ,
            ];

            $result_reserved['Товары'] = [
                'type' => 'cart items',
                'value' => $reserved,
            ];

            $result['Код'] = [
                'type' => 'string',
                'value' => uniqid(),
            ]; 

            $result['Способ получения'] = [
                'type' => 'string',
                'value' => "Самовывоз",
            ]; 
        }

        $result = [
            "ready" => json_encode($result),
            "reserved" => json_encode($result_reserved)
        ];

        return $result;
    }
}
