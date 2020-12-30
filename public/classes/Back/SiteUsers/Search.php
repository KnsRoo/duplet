<?php

namespace Back\SiteUsers;

use Model\User as UserView;

/*
 * class Search
 * класс для пойска записей по модели
 * */
class Search {

    /*
     * by
     * Метод формирует Qb по модели, параметрому которому нужно и искать, и по 
     * по полям что не должны входить в поиск
     * @param Class $class
     * @param String $query
     * @param Array $exclude
     * return Qb
     * */
    public static function by($class, $query, $exclude = []) {

        $fields = get_object_vars($class);
        unset($fields['_error']);

        foreach ($exclude as $value)
            if (array_key_exists($value, $fields))
                unset($fields[$value]);

        $last = count($fields) - 1;
        $counter = 0;

        $result = '';

        $params = [':query' => '%' . $query . '%'];

        foreach ($fields as $key => $field) {

            $query = '`' . $key . '`  LIKE :query';

            if ($counter == 0)
                $result = $class::find($query, $params);
            else
                $result->orWhere($query, $params);

            $counter++;

        }

        return $result;

    }

}
