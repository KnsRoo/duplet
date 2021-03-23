<?php

namespace API\Pages\V1\Factory\HAL;

use Websm\Framework\Router\Router;

use Model\Catalog\Product as ProductItem;

class Props {

    public static function getLinks($params) {

        $item = $params['item'];

        $protocol = 'http';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
            $protocol = 'https';

        $origin = $protocol . '://' . $_SERVER['HTTP_HOST'];

        $routes = [
            'self' => Router::byName('api:pages:v1:props')
                ->getAbsolutePath(['id' => $item->id])
        ];

        return [
            'self' => [
                'href' => $origin . $routes['self'],
            ]
        ];
    }

    public static function getEmbedded($params) {

        $item = $params['item'];

        $props = json_decode($item->props, true);

        $result = [];

        foreach ($props as $key => $prop) {
            $result[$key]['type'] = $prop['type'];

            if ($prop['type'] == 'color'){
                $value = json_decode($prop['value']);
                $result[$key]['value'] = $value;
            } elseif ($prop['type'] == 'images') {
                $result[$key]['value'] = self::getImages(['prop' => $prop]);
            } elseif ($prop['type'] == 'products') {
                $result[$key] = self::getProducts(['prop' => $prop]);
            } else {
                $result[$key]['value'] = $prop;
            }  

        }

        return [ 'props' => $result ];
    }

    public static function get($params) {

        $item = $params['item'];

        $result['_links'] = self::getLinks(['item' => $item]);
        $result['_embedded'] = self::getEmbedded(['item' => $item]);

        return $result;
    }

    private static function getImages($params) {

        $prop = (Object)$params['prop'];

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

                $file = \Model\FileMan\File::find(['id' => $id])
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

        $prop = (Object)$params['prop'];

        $res = (Object)[
            'type' => 'products',
            'value' => [],
        ];

        $urls = [];

        if (key_exists('value', $prop) && is_array($prop->value)) {

            $ids = $prop->value;
            $images = [];

            foreach($ids as $id) {

                $item = ProductItem::find(['id' => $id])->get();

                $res->value[] = \API\Catalog\V3\Factory\HAL\Product::get(['item' => $item]);
            }
        }
        return $res;
    }
}
