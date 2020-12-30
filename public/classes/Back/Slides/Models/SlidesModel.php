<?php

namespace Back\Slides\Models;

use \Websm\Framework\Db\ActiveRecord;
use \Model\FileMan\File;
use \Back\Files\Config;

class SlidesModel extends ActiveRecord
{
    public static $table = 'slides';

    public $id = null;
    public $title = 'Новый слайд';
    public $sort = 0;
    public $preview = '';
    public $visible = true;
    public $picture = null;
    public $link = '';
    public $link_text = null;

    public function getRules()
    {
        return [
            ['id', 'required'],
            ['id', 'match', 'exp' => '/^\w*$/'],
            [['title', 'sort', 'picture'], 'native'],
            ['sort', 'int'],
            ['picture', 'striplace', 'on' => ['create', 'update']],
            [['title', 'preview', 'link', 'link_text'], 'stripTags', 'on' => ['create', 'update']],
            [
                'title', 'length', 'min' => 2,
                'message' => 'Для созания товара, название должно быть более 2х символов'
            ],
            ['visible', 'boolean'],
        ];
    }

    public function getPicture($resolution = '150x150')
    {
        $file = File::find(['id' => $this->picture])->get();
        if ($file->isPicture())
            return Config::PREFIX_PATH . '/' . $file->getPicture($resolution);
        else return null;
    }

    public static function getVisibleSlides()
    {
        return self::find(['visible' => 1])
            ->order('sort')
            ->getAll();
    }
}