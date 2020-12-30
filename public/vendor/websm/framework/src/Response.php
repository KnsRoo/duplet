<?php

namespace Websm\Framework;

class Response {

    private static $responseData = [
        'css' => [],
        'js' => [],
        'inlineJs' => '',
        'inlineCss' => '',
        'notify' => [],
        'content' => '',
        'actions' => '',
        'status' => 'ok',
    ];

    public function __set($key, $data) { self::$responseData[$key] = $data; }

    public function &__get($key) { return self::$responseData[$key]; }

    public function setLink($key, &$data) { self::$responseData[$key] = &$data; }

    public function send($data = '') { die($data); }

    public function render($temp = '', Array $_data = []) {

        if(is_file($temp)){
            $_data && extract($_data, EXTR_REFS);
            ob_start();
            include $temp;
            return ob_get_clean();
        }
        return false;

    }

    public function back() {

        header('Location: '.$_SERVER['HTTP_REFERER']);
        die();

    }

    public function code($code = 200) {

        return http_response_code($code);

    }

    public function location($location = '/') {

        header('Location: '.$location);
        die();

    }

    public function json($data = null) {

        header('Content-Type: application/json');
        $data = json_encode(!is_null($data) ? $data : (Object)self::$responseData);
        die($data);

    }

    public function jsonp($data = [], $callback = '') {

        !$callback && $callback = &$_GET['callback'];
        $callback = $callback ? $callback : 'var lastResponse = ';
        die($callback.'('.json_encode($data ? $data : (Object)self::$responseData).');');

    }

    public function hal($data) {

        header('Content-Type: application/hal+json');
        die(json_encode($data));
    }

    public function htmlSC($str) {

        return htmlspecialchars($str);

    }

    public function rmTags($str) {

        return strip_tags($str);

    }

}
