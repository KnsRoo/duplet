<?php

namespace API\Feedback\V1\Factory;

class QueryParams {

    const ORDER_ANSWER_FIELDS = [ 
        'creationDate' => 'creation_date',
        'updateDate' => 'update_date',
        'sort' => 'sort',
    ];

    const ORDER_QUESTION_FIELDS = [ 
        'sort' => 'sort',
        'creationDate' => 'creation_date',
        'updateDate' => 'update_date',
    ];

    const ORDER_TYPES = [ 
        'asc' => 'ASC',
        'desc' => 'DESC',
    ];

    public static function getOffset($default = 0) {

        $offset = isset($_GET['offset']) ? (Integer)$_GET['offset'] : $default;
        return $offset;
    }

    public static function getLimit($default = 30, $max = 1000) {

        $limit = isset($_GET['limit']) ? (Integer)$_GET['limit'] : $default;
        $limit = min($limit, $max);
        return $limit;
    }

    public static function getOrderQuestions() { 
        $orderRaw = isset($_GET['order']) ? $_GET['order'] : [];

        $order = [];

        if (!is_array($orderRaw)) {
            $order = [ 'creation_date' => 'DESC' ];
        } else {

            foreach($orderRaw as $key => $value) {

                if (array_key_exists($key, self::ORDER_QUESTION_FIELDS)) {

                    if (array_key_exists($value, self::ORDER_TYPES)) {
                        $order[self::ORDER_QUESTION_FIELDS[$key]] = self::ORDER_TYPES[$value];
                    } else {
                        $order[self::ORDER_QUESTION_FIELDS[$key]] = 'ASC';
                    }
                }
            }
        }

        $orderFmt = [];
        foreach($order as $key => $value)
            $orderFmt[] = '`' . $key . '` ' . $value; 

        return $orderFmt;
    }

    public static function getOrderAnswers() {

        $orderRaw = isset($_GET['order']) ? $_GET['order'] : [];

        $order = [];

        if (!is_array($orderRaw)) {
            $order = [ 'creation_date' => 'DESC' ];
        } else {

            foreach($orderRaw as $key => $value) {

                if (array_key_exists($key, self::ORDER_ANSWER_FIELDS)) {

                    if (array_key_exists($value, self::ORDER_TYPES)) {
                        $order[self::ORDER_ANSWER_FIELDS[$key]] = self::ORDER_TYPES[$value];
                    } else {
                        $order[self::ORDER_ANSWER_FIELDS[$key]] = 'ASC';
                    }
                }
            }
        }

        $orderFmt = [];
        foreach($order as $key => $value)
            $orderFmt[] = '`' . $key . '` ' . $value; 

        return $orderFmt;
    }

    public static function getTags() {

        $tags = isset($_GET['tags']) ? $_GET['tags'] : [];
        if (!is_array($tags))
            $tags = [];

        return $tags;

    }
}
