<?php

namespace API\Catalog\V3\Factory\HAL;

use Websm\Framework\Router\Router;

use Model\Catalog\Group as ModelGroup;

class Group {

    public static function getLinks($params) {

        $item = $params['item'];

        return [
            'self' => [
                'href' => Router::byName('api:catalog:v3:group')
                    ->getURL([ 'id' => $item->id ]),
            ],
            'subproducts' => [
                'href' => Router::byName('api:catalog:v3:subproducts')
                    ->getURL([ 'id' => $item->id ]),
            ],
            'subgroups' => [
                'href' => Router::byName('api:catalog:v3:subgroups')
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

        $id = $item->cid;

        $parent = $item;

        $path[] = [
            'id' => $item->id,
            'title' => $item->title,
            'subproducts' => Router::byName('api:catalog:v3:subproducts')->getURL(['id' => $item->id]),
        ];

        while ($id != NULL){
            $parent = ModelGroup::find(['id' => $id])->get();
            $id = $parent->cid;
            $path[] = [ 
                'id' => $parent->id,
                'title' => $parent->title,
                'subgroups' => Router::byName('api:catalog:v3:subgroups')->getURL(['id' => $parent->id]),
                'subproducts' => Router::byName('api:catalog:v3:subproducts')->getURL(['id' => $parent->id]),
                //'child' => $path,
            ];
        }

        // $path[] = [
        //     'id' => 'root',
        //     'title' => 'Все категории',
        //     'subproducts' => Router::byName('api:catalog:v3:groups')->getURL(),
        //     //'child' => $path
        // ];

        return [
            'id' => $item->id,
            'path' => array_reverse($path),
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
