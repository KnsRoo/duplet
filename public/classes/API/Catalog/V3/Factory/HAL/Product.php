<?php

namespace API\Catalog\V3\Factory\HAL;

use Websm\Framework\Router\Router;

class Product {

    public static function getLinks($params) {

        $item = $params['item'];

        return [
            'self' => [
                'href' => Router::byName('api:catalog:v3:product')
                    ->getURL([ 'id' => $item->id ]),
            ],
            'group' => [
                'href' => Router::byName('api:catalog:v3:group')
                    ->getURL([ 'id' => $item->cid ]),
            ],
        ];
    }

    public static function getEmbedded($params) {

        return (Object)null;
    }

    public static function get($params) {

        $item = $params['item'];

        $picSize = 500;
        $picture = $item->getPicture($picSize . 'x' .$picSize);
        $tags = explode(':', trim($item->tags, ':'));
        if ($tags[0] === "") $tags = [];

        $route = Router::byName('catalog:product');
        $pageRef = $route->getURL(['productId' => $item->id]);

        return [
            'id' => (String)$item->id,
            'title' => mb_ereg_replace('\\\"','"',(String)$item->title),
            'pageRef' => $pageRef,
            'code' => (String)$item->code,
            'price' => (float)$item->price,
            'discount' => $item->discount ? $item->discount : "0",
            'exists' => self::checkExists(['item' => $item]),
            'discount_price' => $item->getDiscountPrice(),
            'category' => $item->getCategory(),
            'discount_text' => $item->discount_text ? (String)$item->discount_text : "",
            'preview' => (String)$item->preview,
            'about' => (String)$item->about,
            'picture' => $picture,
            'creationDate' => $item->date,
            'sort' => (Integer)$item->sort,
            'tags' => $tags,
            'props' => self::getProps(['item' => $item]),
            '_links' => self::getLinks(['item' => $item]),
            /* '_embedded' => self::getEmbedded(['item' => $item]), */
        ];
    }

    public static function checkExists($params) {
        $item = $params['item'];
        $props = (Object)json_decode($item->props);
        if (!(array_key_exists('??????????????', $props))){
            return false;
        }
        $remains = $props->??????????????->value; 
        if (!$remains) return false;
        $result = false;
        foreach ($remains as $key => $value) {
            if ((int)$value > 0){
                $result = true;
            }
        }
        return $result;
    }

    public static function getProps($params) {

        $item = $params['item'];
        $props = (Object)json_decode($item->props);
        $result = (Object)null;

        foreach($props as $key => $prop) {

            if ($prop->type == 'images') {
                $prop = self::getImages(['prop' => $prop]);
            } elseif ($prop->type == 'products') {
                $prop = self::getProducts(['prop' => $prop]);
            }

            if ($prop == "??????????????"){

            }

            $result->$key = $prop;
        }

        return $result;
    }

    private static function getImages($params) {

        $prop = $params['prop'];

        $picSize = 500;
        $resolution = $picSize . 'x' . $picSize;

        $res = (Object)[
            'type' => 'images',
            'value' => [],
        ];

        if (key_exists('value', $prop) && is_array($prop->value)) {

            $ids = $prop->value;
            $images = [];

            foreach($ids as $id) {

                $file = \Model\Fileman\File::find(['id' => $id])
                    ->get();

                $protocol = 'http';

                if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
                    $protocol = 'https';

                $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

                $prefix = $origin . \Back\Files\Config::PREFIX_PATH.'/';

                if ($file->isPicture()) {
                    $images[] = $prefix . $file->getPicture($resolution);
                } else if($file->isVPicture()) {
                    $images[] = $prefix . $file->getPicture('');
                }
            }

            $res->value = $images;
        }

        return $res;
    }

    private static function getProducts($params) {

        $prop = $params['prop'];

        $res = (Object)[
            'type' => 'products',
            'value' => [],
        ];

        $urls = [];
        if (key_exists('value', $prop) && is_array($prop->value)) {

            $ids = $prop->value;
            $images = [];

            foreach($ids as $id) {

                $route = \Websm\Framework\Router\Router::byName('api:catalog:v3:product');
                $urls[] = $route->getURL(['id' => $id]);

            }

            $res->value = $urls;
        }

        return $res;
    }
}
