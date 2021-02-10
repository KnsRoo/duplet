<?php

namespace API\User\V1\Factory\HAL;

use Websm\Framework\Router\Router;
use Websm\Framework\Di;

class User {

    public static function getLinks($params) {

        $item = $params['item'];

        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('api:user:v1')->getURL(),
            'email' => Router::byName('api:user:v1:email')->getURL(),
            'phone' => Router::byName('api:user:v1:phone')->getURL(),
            'props' => Router::byName('api:user:v1:props')->getURL(),
            'orders' => Router::byName('api:user:v1:orders')->getURL(),
            //'discount-cards' => Router::byName('api:discount-cards:v1:cards')->getURL(),
        ];

        $res = [];

        foreach ($routes as $key => $url) {
            $res[$key] = [ 'href' => $url ];
        }

        return $res;
    }

    public static function getEmbedded($params) {

        $item = $params['item'];
        $embedded = new \stdClass;

        $embedded->props = Props::get([
            'items' => (Object)json_decode($item->props),
        ]);

        return $embedded;
    }

    public static function get($params) {

        $item = $params['item'];

        $result = [
            'id' => $item->id,
            'creationDate' => $item->creation_date,
            'updateDate' => $item->update_date,
        ];

        $phone = null;
        $email = null;

        $di = Di\Container::instance();
        $crypt = $di->get('crypt');

        if ($item->phone !== null)
            $phone = $crypt->decrypt($item->phone);

        if ($item->email !== null)
            $email = $crypt->decrypt($item->email);

        $result['phone'] = $phone;
        $result['email'] = $email;

        $result['_links'] = self::getLinks(['item' => $item]);
        $result['_embedded'] = self::getEmbedded(['item' => $item]);

        return $result;
    }
}
