<?php

namespace API\Catalog\V2\Factory\HAL;

use Websm\Framework\Router\Router;

class Group {

    public static function getLinks($params) {

        $item = $params['item'];

        return [
            'self' => [
                'href' => Router::byName('api:catalog:v2:group')
                    ->getURL([ 'id' => $item->id ]),
            ],
            'subproducts' => [
                'href' => Router::byName('api:catalog:v2:subproducts')
                    ->getURL([ 'id' => $item->id ]),
            ],
            'subgroups' => [
                'href' => Router::byName('api:catalog:v2:subgroups')
                    ->getURL([ 'id' => $item->id ]),
            ],
        ];
    }

    public static function getEmbedded($params) {

        return [];
    }

    public static function get($params) {

        $item = $params['item'];

        $picSize = 500;
        $picture = $item->getPicture($picSize . 'x' .$picSize);

        $route = Router::byName('catalog:group');
        $pageRef = $route->getURL([ 'chpu' => $item->chpu ]);

        return [
            'id' => $item->id,
            'title' => $item->title,
            'code' => $item->code,
            'preview' => $item->preview,
            'picture' => $picture,
            'pageRef' => $pageRef,
            '_links' => self::getLinks([ 'item' => $item ]),
            '_embedded' => self::getEmbedded([ 'item' => $item ]),
        ];
    }
}
