<?php

namespace Model\Catalog;

use Websm\Framework\Db\ActiveRecord;
use Websm\Framework\Path\PathProviderInterface;
use Model\FileMan\File;
use Back\Files\Config;

use DateTime;

class Product extends ActiveRecord implements PathProviderInterface
{

    use \Model\Tags\TagsTrait;

    public static $table = 'catalog_product';

    public $id = null;
    public $cid = null;
    public $title = 'Новый товар';
    public $meta_title = '';
    public $price = 0;
    public $hash = null;
    public $preview = '';
    public $keywords = '';
    public $about = '';
    public $picture = null;
    public $code = null;
    public $visible = true;
    public $sort = 1;
    public $chpu = '';

    public $props;

    private $category;

    public function getRules()
    {
        return [

            ['id', 'required', 'on' => 'create'],

            ['cid', 'native'],

            ['code', 'native'],

            [
                'title', 'length', 'min' => 2,
                'message' => 'Название должно быть более 2х символов.'
            ],

            ['title', 'native'],

            ['meta_title', 'native'],

            ['price', ['native', 'numeric']],

            ['hash', 'required'],

            ['preview', 'pass'],

            ['keywords', 'stripTags'],

            ['about', 'pass'],

            ['picture', 'native'],

            ['visible', 'boolean'],

            ['sort', ['native', 'int'] ],

            ['chpu', 'required'],

            ['tags', 'serializeTagsValidator'],

            ['props', 'pass'],

        ];
    }

    public function getCategory()
    {

        return Group::find(['id' => $this->cid])->get();
    }

    public function getPicture($resolution = '150x150')
    {

        $file = File::find(['id' => $this->picture])->get();

        if ($file->isPicture())
            return Config::PREFIX_PATH . '/' . $file->getPicture($resolution);

        else return null;
    }

    public function getImageStyle($resolution = '500x500')
    {

        $picture = $this->getPicture($resolution);
        $style = $picture ? ' style="background-image: url(\'' . $picture . '\');"' : '';

        return $style;
    }

    public function getDate($format = 'd.m.Y H:i:s')
    {

        return (new DateTime($this->date))
            ->format($format);
    }

    public static function getAllCid($id)
    {

        return self::find()
            ->where(['cid' => $id])
            ->getAll();
    }

    public function getExtraPropertiesArray()
    {

        $res = json_decode($this->extra_properties, true);
        return $res ?: [];
    }

    public function getParent()
    {
        if (!$this->cid) return false;
        return Group::find(['id' => $this->cid])->get();
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getRef()
    {

        return '/Katalog/' . $this->chpu;
    }

    public function isRoot()
    {

        return $this->cid === null;
    }
}
