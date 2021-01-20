<?php

namespace Model\Catalog;

use Websm\Framework\Db\ActiveRecord;

class Childs extends ActiveRecord
{

    public static $table = 'catalog_childs';

    public $id;
    public $childs;

    public function getRules()
    {
        return [
            ['id', 'pass'],
            ['childs', 'pass'],
        ];
    }

    public function getChildArray(){
        $childsStr = self::find(['id' => $this->id])->get()->childs;
        $childsStr = substr($childsStr,1,strlen($childsStr)-2);
        $arr = explode(',',$childsStr);
        foreach ($arr as $string) {
            $arr2[] = substr($string,1,strlen($string)-2);
        }
        return $arr2;

    }
}
