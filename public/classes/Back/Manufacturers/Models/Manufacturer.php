<?php

namespace Back\Manufacturers\Models;

use \Websm\Framework\Db\ActiveRecord;
use \Model\FileMan\File;
use Back\Files\Config as FilesConfig;
use Exception;

class Manufacturer extends ActiveRecord {

    public static $table = 'manufacturers';

    public $id = null;
    public $name = 'Без имени';
    public $picture = null;
    public $url = null;

    public function getRules(){
        return [
            ['id', 'required'],
            ['name', 'required'],
            ['name', 'stripTags'],
            ['picture', 'native'],
            /* ['url', 'urlFormat', 'on' => 'update', */
            /*     'message' => 'url должен начинаться с http:// или https://'], */
            ['url', 'stripTags'],
            /* ['sort', 'pass', 'on' => 'create'], //для парсера */
            ['sort', 'int', 'on' => 'sort',
                'message' => 'Значение должно быть целым положительным числом'],
            ['sort', 'limit', 'on' => 'sort', 'min' => 0,
                'message' => 'Значение должно быть целым положительным числом'],
        ];
    }

    public function urlFormat($field = 'url', $params) {

        return !!preg_match('/^(http|https):\/\//i', $this->$field);

    }

    public function getPicture($type = 'small') {

        $picture = null;
        $file = File::find(['id' => $this->picture])->get();
        if($file->isPicture()) {

            switch($type) {

            case 'small':
                $picture = $file->getSmallPicture();
                break;

            case 'big':
                $picture = $file->getBigPicture();
                break;

            default:
                $picture = $file->getPicture($type);
                break;

            }

        }

        $prefix = FilesConfig::PREFIX_PATH.'/';

        return $picture ? $prefix.$picture : null;

    }

    public static function all() {

        return self::find()->order('sort')->getAll();

    }

}
