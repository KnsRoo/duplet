<?php

namespace Websm\Framework;

class GreCAPTCHA {

    private $siteKey;
    private $secretKey;

    public function __construct($secretKey, $siteKey) {

        $this->secretKey = $secretKey;
        $this->siteKey = $siteKey;
    }

    public function verify($token) {
    
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

        else $ip = $_SERVER['REMOTE_ADDR'];

        $postdata = http_build_query([
            'secret' => $this->secretKey,
            'response' => $token,
            'remoteip' => $ip,
        ]);

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata,
            ]
        ]);

        $result = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
        $response = json_decode($result);

        return $response->success;
    }

    public function getSiteKey() {

        return $this->siteKey;
    }
}
