<?php

namespace Websm\Framework;

class StringF{

    /**
     * @brief Обрежет строку по словам.
     * @param $text Указатель на строку.
     * @param $count Сколько слов получить на выходе.
     * @return String
     */

    public static function cut(&$text, $count = 30){

        $text = htmlspecialchars(strip_tags($text));
        $text = preg_replace('/(&[\w\;]{3,}\;|\n|\t)/', ' ', $text);
        $text = preg_replace('/\s{2,}/', ' ', $text);
        preg_match_all('/[\w\,\.\!\?]{1,}/u', $text, $arr);
        $wordsarr = array_slice($arr[0], 0, $count);
        $text = implode(' ', $wordsarr);

        return $text;

    }


    /**
     * @brief Обрежет строку до ближайшей точки.
     * @param $text Строка.
     * @param $count Гарантированное количество символов на выходе.
     * @return String
     */

    public static function cutToADot($text, $min = 30) {

        $ret = strip_tags($text);
        $ret = html_entity_decode($ret);
        $ret = preg_replace('/\s{2,}/', ' ', $ret);
        $ret = preg_replace('/^(.{'.$min.',}?\.).*$/us', '$1', $ret);
        $ret = htmlspecialchars($ret);

        return $ret;

    }

}
