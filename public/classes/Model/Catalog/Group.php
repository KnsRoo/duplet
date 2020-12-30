<?php

namespace Model\Catalog;

use Websm\Framework\Db\ActiveRecord;
use Websm\Framework\Path\PathProviderInterface;

use Back\Files\Config as FilesConfig;

class Group extends ActiveRecord implements PathProviderInterface
{

    public static $table = 'catalog_group';

    use \Model\Tags\TagsTrait;

    public $id = null;
    public $cid = null;
    public $code = null;
    public $title = 'Новый каталог';
    public $meta_title = '';
    public $hash = null;
    public $preview = '';
    public $keywords = '';
    public $picture = null;
    public $about = '';
    public $sort = 0;
    public $visible = true;
    public $chpu = '';

    public function getRules()
    {
        return [
            ['id', 'required', 'on' => 'create'],
            ['cid', 'native'],
            ['code', 'native'],
            ['title', 'native'],
            ['hash', 'required'],
            ['preview', 'pass'],
            ['keywords', 'stripTags'],
            ['meta_title', 'stripTags'],
            ['picture', 'native'],
            ['about', 'pass'],
            ['sort', ['native', 'int']],
            ['visible', 'boolean'],
            ['chpu', 'required'],
            ['tags', 'serializeTagsValidator'],
        ];
    }

    public function getPicture($resolution = '700x700')
    {
        $file = \Model\FileMan\File::find(['id' => $this->picture])->get();

        if ($file->isPicture()) {

            return FilesConfig::PREFIX_PATH . '/' . $file->getPicture($resolution);
        } else return null;
    }

    public function getImageStyle($resolution = '700x700')
    {
        $picture = $this->getPicture($resolution);
        $style = $picture ? ' style="background-image: url(\'' . $picture . '\');"' : '';

        return $style;
    }

    public function groups()
    {
        return self::find(['cid' => $this->id])
            ->order(['sort ASC']);
    }

    public function getGroups()
    {
        return self::find(['cid' => $this->id])->genAll();
    }

    public function getVisibleGroups()
    {
        return self::find(['cid' => $this->id, 'visible' => true])->getAll();
    }

    public function getGroupsCount()
    {
        return self::find(['cid' => $this->id])->count();
    }

    public function products()
    {
        return Product::find(['cid' => $this->id])
            ->order(['sort ASC']);
    }

    public function getProducts()
    {
        return Product::find(['cid' => $this->id])->genAll();
    }

    public function getVisibleProducts()
    {
        return Product::find(['cid' => $this->id, 'visible' => true])->getAll();
    }

    public function getProductsCount()
    {

        return Product::find(['cid' => $this->id])->count();
    }

    /* public static function getTitle($title) { */

    /*     return self::find() */
    /*         ->where(['title' => $title]) */
    /*         ->get(); */

    /* } */

    public function getParent()
    {
        if (!$this->cid) return false;
        return self::find(['id' => $this->cid])->get();
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getRef()
    {
        return $this->chpu;
    }

    public function isRoot()
    {
        return $this->cid === null;
    }
}
