<?php

namespace Model\FileMan;

use Websm\Framework\Db\ActiveRecord;
use Websm\Framework\Db\Config as GConfig;
use Core\Users;
use Websm\Framework\ImgResize;
use Websm\Framework\VideoResizer;
use Back\Files\Config;

use Websm\Framework\Di;

class Folder extends ActiveRecord {

    public $id = null;
    public $cid = null;
    public $title = 'Без имени';

    public static function getDb(){

        return GConfig::get('files');

    }

    public function getRules(){

        return [

            ['id', 'required'],
            ['cid', 'native'],
            ['title', ['stripTags', 'native']],
            [
                'title', 'length', 'min' => 2,
                'message' => 'Название папки длжно быть минимум 2 символа.',
            ],
            ['date', 'numeric'],
            [['user', 'keywords'], 'stripTags'],

        ];

    }

    /**
     * belongs Проверит принадлежность к папке.
     * 
     * @param mixed $id 
     * @access public
     * @return bool
     */
    public function belongs($id = null) {

        if ($id == $this->cid) return true;

        $page = $this->getParent();

        if (!$page) return false;
        else return $page->belongs($id);

    }

    public function getParent() {

        if (!$this->cid) return false;
        return self::find(['id' => $this->cid])->get();

    }

    public function getFiles(){

        return File::find(['cid' => $this->id])
            ->order(['date DESC'])
            ->limit(80);

    }

    public function getFolders(){

        return self::find(['cid' => $this->id])
            ->order(['date DESC'])
            ->genAll();

    }

    public function files(){

        return File::find(['cid' => $this->id])
            ->order(['date DESC']);

    }

    public function folders(){

        return self::find(['cid' => $this->id])
            ->order(['date DESC']);

    }

    public function delete(){

        $result = parent::delete();
        if (!$result) return false;

        $gen = File::find('`no_del` IS NULL AND `cid` IS NOT NULL')->genAll();

        foreach($gen as $file){
            $file->delete();
        }

        return true;

    }

    public function createFile($src, $title = 'Без имени'){

        if (!file_exists($src) || !is_file($src))
            throw new \Exception('$src is not file.');

        $di = Di\Container::instance();
        $file = new File;
        $file->id = md5(uniqid());
        $file->cid = $this->id;
        $file->no_del = $this->id;
        $file->date = date('U');
        $file->text_date = date('Y-m-d H:i:s');

        preg_match('/^(?<title>.*)(\.(?<ext>\w+))?$()/Uu', $title, $matches);
        $file->title = $matches['title'];

        $file->ext = mb_strtolower($matches['ext'], 'UTF-8');
        $file->size = filesize($src);

        $user = Users::get();
        if ($user) $file->user = $user->login;

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $file->type = $finfo->file($src);

        if ($file->save()) {

            if ($file->isPicture()) {

                $dst = Config::DATA_PATH.'/'.$file->getPictureName();
                ImgResize::resize($src, $dst);
                $file->size = filesize($dst);
                $file->save();

            } elseif ($file->isVideo()) {

                $dst = Config::DATA_PATH.'/'.$file->getVideoName();

                if ($di->has('video-queue')) {

                    $tmpfname = tempnam(sys_get_temp_dir(), $file->id);
                    rename($src, $tmpfname);

                    $message = [
                        'in' => $tmpfname,
                        'out' => $dst,
                        'quality' => VideoResizer::QUALITY_720,
                    ];

                    $queue = $di->get('video-queue');
                    $queue->push($message);

                    $file->size = filesize($tmpfname);
                    $file->save();

                } else {

                    $resizer = new VideoResizer;
                    $result = $resizer->resize($src, $dst);

                    if (!$result) {

                        $file->delete();
                        return false;

                    } else {

                        $file->size = filesize($dst);
                        $file->save();

                    }

                }


            } else {

               @rename($src, Config::DATA_PATH.'/'.$file->id.'.'.$file->ext);

            }

            return $file;

        } else {

            $this->_errors = $file->_errors;
            return false;

        }

    }

}
