<?php

namespace Model\FileMan;

use Websm\Framework\Db\ActiveRecord;
use Websm\Framework\Db\Config as GConfig;
use Back\Files\Config;
use Websm\Framework\ImgResize;
use Websm\Framework\VideoResizer;
use Exception;

class File extends ActiveRecord {

    public $id = null;
    public $cid = null;
    public $no_del = null;
    public $title = 'Без имени';

    public static function getDb(){

        return GConfig::get('files');

    }

    public function isPicture(){

        return in_array($this->type, Config::IMAGE);

    }

    public function isVPicture(){

        return in_array($this->type, Config::VECTOR_IMAGE);

    }

    public function isDoc(){

        return in_array($this->type, Config::DOC);

    }

    public function isVideo() {

        return in_array($this->type, Config::VIDEO);

    }

    public function isArchive(){

        return in_array($this->type, Config::ARCHIVE);

    }

    public function getRules(){
        return [
            ['id', 'required'],
            ['cid', 'native'],
            [ ['date', 'size'], 'numeric'],
            ['text_date', 'match', 'exp' => '/^\d{4}\-\d{2}\-\d{2}\s\d{2}:\d{2}:\d{2}$/'],
            [ ['title', 'user'], 'stripTags'],
            ['no_del', 'native'],
            ['ext', 'isAllowedExt', 'message' => 'Недопустимое расширение файла.'],
            ['type', 'isAllowedType', 'message' => 'Недопустимый тип файла.'],
        ];
    }

    public function isAllowedExt($field = 'ext', $params){

        return in_array($this->$field, Config::EXTENSIONS);

    }

    public function isAllowedType($field = 'type', $params){

        return in_array($this->$field, Config::getMimeTypes());

    }

    public function getVideoName($suffix = ''){

        if(!is_string($suffix))
            throw new Exception('Suffix is not string.');

        return $this->id.($suffix ? '_'.$suffix : '').'.'.$this->ext;

    }

    public function getVideoPreview($size = '') {

        $file = $this->getVideoPreviewName($size);

        if(file_exists(Config::DATA_PATH.'/'.$file)) return $file;
        else {

            $in = Config::DATA_PATH.'/'.$this->getVideoName();
            $out = Config::DATA_PATH.'/'.$this->getVideoPreviewName($size);

            $resizer = new VideoResizer;
            if ($resizer->preview($in, $out, $size)) return $file;
            else return false;

        }

    }

    public function getVideoPreviewName($suffix = ''){

        if(!is_string($suffix))
            throw new Exception('Suffix is not string.');

        return $this->id.'_vp'.($suffix ? '_'.$suffix : '').'.jpg';

    }

    public function getSmallPicture(){

        return $this->getPicture(Config::SMALL_RESOLUTION);

    }

    public function getBigPicture(){

        return $this->getPicture(Config::BIG_RESOLUTION);

    }

    public function getPictureName($suffix = ''){

        if(!is_string($suffix))
            throw new Exception('Suffix is not string.');

        return $this->id.($suffix ? '_'.$suffix : '').'.'.$this->ext;

    }

    public function getName() {

        return $this->id.'.'.$this->ext;

    }

    public function getPicture($resolution){

        $file = $this->getPictureName($resolution);

        if(file_exists(Config::DATA_PATH.'/'.$file)) return $file;
        else {

            $res = explode('x', $resolution);
            ImgResize::resize(
                Config::DATA_PATH.'/'.$this->getPictureName(),
                Config::DATA_PATH.'/'.$this->getPictureName($resolution),
                $res[0],
                $res[1]
            );
            return $file;

        }

    }

    public function getIcon(){

        return 'mime_'.$this->ext.'.svg';

    }

    public function delete(){

        $res = parent::delete();

        if($res){

            array_map('unlink', glob(Config::DATA_PATH.'/'.$this->id.'*'));
            return true;

        }
        else return false;

    }

}
