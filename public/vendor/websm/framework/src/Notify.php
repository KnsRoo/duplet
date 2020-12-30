<?php

namespace Websm\Framework;

class Notify{

    protected static $queue = [];

    public static function init(){

        !isset($_SESSION) && session_start();
        self::$queue = &$_SESSION['Notify'];
        !is_array(self::$queue) && self::$queue = [];

    }

    /**
     * @brief Добавляет уведомдение в конец очереди.
     * @param $text Текст уведомления.
     * @param $header Заголовок уведомления.
     * @return Void
     */

    public static function push($text = '', $type = ''){

        $_SESSION['Notify'][] = ['text' => $text, 'type' => $type];

    }

    public static function pushArray(Array $items = []){
        foreach($items as $item)
            self::push($item['text'], $item['type']);
    }

    /**
     * @brief Достает уведомление с конца очереди.
     * @return Array
     */

    public static function pop(){

        return array_pop($_SESSION['Notify']);

    }

    /**
     * @brief Достанет все уведомления с конца очереди.
     * @return Array
     */

    public static function popAll(){

        $ret = $_SESSION['Notify'];
        self::clear();
        return $ret;

    }

    /**
     * @brief Достает уведомление с начала очереди.
     * @return Array
     */

    public static function shift(){

        return array_shift($_SESSION['Notify']);

    }

    /**
     * @brief Достанет все уведомления c начала очереди.
     * @return Array
     */

    public static function shiftAll(){

        $ret = array_reverse($_SESSION['Notify']);
        self::clear();
        return $ret;

    }

    /**
     * @brief Возвращает указатель на очередь уведомлений.
     * @return Array
     */

    public static function &get(){ return $_SESSION['Notify']; }

    /**
     * @brief Псевдоним push.
     * @see push
     */

    public static function add($text = '', $header = ''){ $this->push($text, $header); }

    /**
     * @brief Очищает очереь уведомлений.
     * @return Void
     */

    public static function clear(){ $_SESSION['Notify'] = []; }

}
