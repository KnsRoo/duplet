<?php

namespace Back\SiteUsers\API\V1\Factory\HAL;

use Back\SiteUsers\API\V1\Factory\QueryParams;
use Websm\Framework\Router\Router;
use Websm\Framework\Di\Container as Di;

class User {

    public static function getLinks($params) {

        $item = $params['item'];
        $origin = 'https://' . $_SERVER['SERVER_NAME'];

        $routes = [
            'self' => Router::byName('api:siteusers:v1:user')
                ->getAbsolutePath([ 'id' => $item->id ]),
        ];

        return [
            'self' => [
                'href' => $origin . $routes['self'],
            ],
        ];
    }

    public static function getEmbedded($params) {

        $item = $params['item'];
        $embed = QueryParams::getEmbed();

        $result = (Object)null;

        if (in_array('props', $embed))
            $result->props = Props::get([ 'item' => $item ]);

        return $result;
    }

    public static function get($params) {

        $item = $params['item'];

        $di = Di::instance();

        $crypt = $di->get('crypt');

        $id = $item->id;
        $phone = $crypt->decrypt($item->phone) ? $crypt->decrypt($item->phone) : null;
        $email = $crypt->decrypt($item->email) ? $crypt->decrypt($item->email) : null;
        $creation_date = $item->creation_date;
        $update_date = $item->update_date;

        $props = json_decode($item->props, true);

        return [
            'id' => (Integer)$id,
            'phone' => $phone,
            'email' => $email,
            'creation_date' => $creation_date,
            'update_date' => $update_date,
            'props' => $props,
            '_links' => self::getLinks(['item' => $item]),
            '_embedded' => self::getEmbedded(['item' => $item]),
        ];
    }
}
