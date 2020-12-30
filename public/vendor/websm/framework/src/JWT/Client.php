<?php

namespace Websm\Framework\JWT;

use Websm\Framework\Exceptions\HTTP as HTTPException;

class Client {

    private $socket;
    private $params = [
        'accessPath' => '/generator',
        'refreshPath' => '/refresher',
        'decodePath' => '/decoder',
    ];

    public function __construct($socket = 'jwt-sign-server.websm.io', $params = []) {

        $this->socket = $socket;
        $this->params = array_merge($this->params, $params);
    }

    public function getTokens($payload) {

        $ch = curl_init();
        $url = $this->socket . $this->params['accessPath'];

        curl_setopt($ch, CURLOPT_URL, $url);
        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
        ];

        $body = json_encode($payload);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        /* curl_setopt($ch, CURLOPT_HTTPGET, true); */
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET' );

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $res = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
        curl_close($ch);


        $res = json_decode($res);
        if ($status == '200') return $res;
        else throw new HTTPException($res, $status);
    }

    public function refreshToken($token) {

        $ch = curl_init();
        $url = $this->socket . $this->params['refreshPath'];

        curl_setopt($ch, CURLOPT_URL, $url);
        $body = json_encode(['refreshToken' => $token]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        /* curl_setopt($ch, CURLOPT_HTTPGET, true); */
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET' );

        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $res = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
        curl_close($ch);

        if ($status == '200') return json_decode($res);
        else throw new HTTPException($res, $status);
    }

    public function decodeToken($token) {

        $ch = curl_init();
        $url = $this->socket . $this->params['decodePath'];

        curl_setopt($ch, CURLOPT_URL, $url);
        $body = json_encode(['token' => $token]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        /* curl_setopt($ch, CURLOPT_HTTPGET, true); */
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET' );

        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
        ];

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $res = curl_exec($ch);

        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
        curl_close($ch);

        if ($status == '200') return $res;
        else throw new HTTPException($res, $status);
    }
}
