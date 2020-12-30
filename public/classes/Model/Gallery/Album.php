<?php 

namespace Model\Gallery;

use Model\FileMan\File;
use Back\Files\Config;

use DateTime;

use Websm\Framework\Path\PathProviderInterface;

class Album extends \Websm\Framework\Db\ActiveRecord implements PathProviderInterface {

    public static $table = 'gallery_album';

    public $id = null;
    public $cid = null;
    public $title = 'Новый альбом';
    public $hash = '';
    public $sort = 0;
    public $visible = true;
    public $picture = null;
    public $chpu = '';
    public $date = '';

    public function getRules(){
        return [
            [ ['id', 'chpu'], 'required'],
            [ ['id', 'cid'], 'match', 'exp' => '/^\w*$/'],
            [ ['title', 'cid', 'sort', 'picture'], 'native'],
            [ 'sort', 'int'],
            [ ['hash', 'picture'], 'striplace', 'on' => ['create', 'update'] ],
            [ ['title', 'preview', 'chpu'], 'stripTags', 'on' => ['create', 'update'] ],
            ['title', 'length', 'min' => 2,
            'message' => 'Для созания альбома, название должно быть более 2х символов'],
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

    public function getPicture($resolution = '269x180'){

        $file = File::find(['id' => $this->picture])->get();
        if($file->isPicture())
            return Config::PREFIX_PATH.'/'.$file->getPicture($resolution);
        else return null;

    }

    public static function getAlbumsCid($id) {

        return Album::find()
            ->where(['cid' => $id])
            ->order('`sort` ASC')
            ->getAll();

    }

    public static function getAlbumId($id) {

        return Album::find()
            ->where(['id' => $id])
            ->get();

    }

    public static function getAllAlbums() {

        return Album::find()
            ->order('`sort` ASC')
            ->getAll();

    }

    public function getPhotosCount(){

        return Photo::find(['cid' => $this->id])->count();

    }


    public function belongs($id = null) {

        if ($id == $this->cid) return true;

        $album = $this->getParent();

        if (!$album) return false;
        else return $album->belongs($id);

    }

    public function getDate($format = 'd.m.Y H:i:s') {

        return (new DateTime($this->date))
            ->format($format);

    }

}
