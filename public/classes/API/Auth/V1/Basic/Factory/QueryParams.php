<?php

namespace API\Auth\V1\Basic\Factory;

class QueryParams {


    public static function getToken($default = NULL) {
        return isset($GET_['token']) ? $GET_['token'] : $default;
    }

    public static function getId($default = NULL) {
        return isset($GET_['id']) ? $GET_['id'] : $default;
    }

}
