<?php 

namespace Model\Gallery;

use Model\FileMan\File;
use Back\Files\Config;

use DateTime;

use Websm\Framework\Path\PathProviderInterface;

class Photo extends \Websm\Framework\Db\ActiveRecord implements PathProviderInterface {

    public static $table = 'gallery_photo';

    public $id = null;
    public $cid = null;
    public $title = 'Новое фото';
    public $preview = '';
    public $sort = 0;
    public $picture = null;
    public $visible = true;
    public $chpu = '';

    public function getRules(){
        return [

            [ ['id', 'cid'], 'match', 'exp' => '/^\w*$/'],
            [ ['id', 'chpu'], 'required'],
            ['cid', 'native'],
            [ ['sort', 'title', 'picture'], 'native'],
            [ ['title', 'preview', 'chpu'], 'stripTags', 'on' => ['create', 'update'] ],
            ['title', 'length', 'min' => 2,
            'message' => 'Для созания фотографии, название должно быть более 2х символов'],
            [ ['sort'], 'int'],
            [ ['hash', 'picture'], 'striplace', 'on' => ['create', 'update'] ],
            ['visible', 'boolean'],
            ['chpu', 'match', 'exp' => '/^[\/\w\-]+$/'],

        ];
    }

    public function getParent() {

        if (!$this->cid) return false;
        return self::find(['id' => $this->cid])->get();

    }

    public function getTitle() {

        return $this->title;

    }

    public function getRef () {

        return '/gallery/' . $this->chpu;

    }

    public function isRoot() {

        return $this->cid === null;

    }

    public static function getPhotosCid($id) {

        return Photo::find()
            ->where(['cid' => $id])
            ->order('`sort` ASC')
            ->getAll();

    }

    public static function getPhotoId($id) {

        return Photo::find()
            ->where(['id' => $id])
            ->get();

    }

    public static function getAllPhotos() {

        return Photo::find()
            ->order('`sort` ASC')
            ->getAll();

    }

    public function getCategory() {

        return Album::find(['id' => $this->cid])->get();

    }

    public function getPicture($resolution = '1030x706'){

        $file = File::find(['id' => $this->picture])->get();
        if($file->isPicture())
            return Config::PREFIX_PATH.'/'.$file->getPicture($resolution);
        else return null;

    }

    public function getDate($format = 'd.m.Y H:i:s') {

        return (new DateTime($this->date))
            ->format($format);

    }

}
