<?php

namespace Back\Files;

use Websm\Framework\Notify;
use Core\Users;
use Websm\Framework\Router\Router;
use Core\Misc\NewPath\NewPath;
use Core\Misc\Pager\Pager;
use Core\ModuleInterface;
use Model\FileMan\File;
use Model\FileMan\Folder;

class Files extends \Websm\Framework\Response implements ModuleInterface {

    const ITEMS_ON_PAGE = 100;

    private $link;

    protected $folder;
    protected $file;
    protected $path;
    protected $parent;
    protected $url = 'files';
    protected $title = 'Файлы';
    protected $page = 1;

    protected $folders = [];
    protected $files = [];

    public $permitions = [
        'chroot' => null,
        'creating-folders' => 'off',
        'uploading-files' => 'off',
        'deleting-folders' => 'off',
        'deleting-files' => 'off',
        'editing-folders' => 'off',
        'editing-files' => 'off',
        'self-only' => 'off',
    ];

    public function setSettings(Array &$props = []) {

        $this->permitions = array_merge($this->permitions, $props);
        $this->permitions['chroot'] = $this->permitions['chroot'] ?: null;

    }

    public function getSettings() { return $this->permitions; }

    public function init($req, $next) {

        $this->css = [
            'css/files.css'
        ];

        $this->js = [
            'js/files.js'
        ];

        $router = Router::instance();

        /* $router->greedy(); */

        $router->addGet('/get-preview-:id', function ($req) {

            $file = File::find(['id' => $req['id']])->get();
            $size = &$_GET['size'];

            if (!$file->isNew()) {

                $preview = '';

                if ($file->isPicture())
                    $preview = $file->getPicture($size);

                elseif ($file->isVideo())
                    $preview = $file->getVideoPreview($size);

                $preview = $preview ? Config::PREFIX_PATH.'/'.$preview : false;
                $this->content = $preview;

            }

            $this->json();

        })->withAjax();

        $router->addAll('/', function($req, $next) {

            $this->page = isset($_GET['page']) ? $_GET['page'] : 1;
            $next();

        }, ['end' => false]);

        $router->addAll('(/folder-:id)?', function($req, $next) {

            $chroot = $this->permitions['chroot'];
            $folderId = $req['id'] ?: $chroot;

            $folder = Folder::find(['id' => $folderId])->get();

            if (!$folder->belongs($chroot))
                $this->folder = Folder::find(['id' => $chroot])->get();

            else $this->folder = $folder;

            $templates = [
                'layout' => __DIR__.'/temp/path/layout.tpl',
                'item'   => __DIR__.'/temp/path/item.tpl'
            ];

            $this->path = NewPath::init($this->folder)
                ->setTemplates($templates)
                ->setData([ 'url' => $this->url ])
                ->getHtml();

            $next();

        }, ['end' => false]);

        $actions = Router::group();

        $actions->addPut('/update-cid/id-:id/cid-:cid', function($req, $next) {

            if ($this->permitions['editing-files'] != 'on') {

                Notify::push('Нет привилегий для перемещения файлов.', 'err');
                $this->back();

            }

            $file = File::find(['id' => $req['id']])->get();
            $folder = Folder::find(['id' => $req['cid']])->get();

            if ($file->isNew() || $folder->isNew()) {

                Notify::push();
                $this->json();

            }

            $file->cid = $req['cid'];

            if($file->save()) {

                Notify::push('Файл успешно перемещен.', 'ok');
                \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                    '<b>'.Users::get()->login.'</b> переместил файл "'.$file->title.'".',
                    'Файлы');

            }
            else Notify::push('Ошибка перемещения файла.', 'err');

        })
            ->where(['id' => '[\w\-]+', 'cid' => '[\w\-]+'])
            ->withAjax();

        $actions->addPost('/create-file', function($req) {

            if ($this->permitions['uploading-files'] != 'on') {

                Notify::push('Нет привилегий для загрузки файлов.', 'err');
                $this->back();

            }

            $folder = $this->folder;

            foreach ($_FILES['files']['name'] as $key => $name) {

                $file = $folder->createFile($_FILES['files']['tmp_name'][$key], $name);

                if ($file) {

                    \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                        '<b>'.Users::get()->login.'</b> загрузил файл "'.$file->title.'".',
                        'Файлы');

                } else {

                    foreach($folder->getErrors() as $error)
                        Notify::pushArray($error);

                }

            }

            $this->back();

        });

        $actions->addPost('/create-folder', function($req) {

            if ($this->permitions['creating-folders'] != 'on') {

                Notify::push('Нет привилегий для создания папок.', 'err');
                $this->back();

            }

            $ar = new Folder();
            $ar->bind($_POST['create']);
            $ar->id = md5(uniqid());
            $ar->cid = $this->folder->id;
            $ar->date = date('U');
            $ar->user = Users::get()->login;

            if($ar->save()) {

                Notify::push('Папка успешно создана.', 'ok');
                \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                    '<b>'.Users::get()->login.'</b> создал папку "'.$ar->title.'".',
                    'Файлы');

            }

            else {

                Notify::push('Ошибка создания папки', 'err');

                foreach($form->getErrors() as $errors)
                    Notify::pushArray($errors);

            }

            $this->back();

        });

        $actions->addPut('/update-title-file-:id', function($req) {

            if ($this->permitions['editing-files'] != 'on') {

                Notify::push('Нет привилегий для изменения файлов.', 'err');
                $this->back();

            }

            $file = File::find(['id' => $req['id']])->get();


            if($file->isNew()){

                Notify::push('Файл не существует.', 'err');
                $this->back();

            }

            $old_title = $file->title;
            $file->title = $_POST['update-title'];

            if($file->save()) {

                Notify::push('Название файла успешно изменено.', 'ok');
                \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                    '<b>'.Users::get()->login.'</b> переименовал файл "'.$old_title.'" в "'.$file->title.'".',
                    'Файлы');

            } else{

                Notify::push('Ошибка изменения названия файла.', 'err');

                foreach($file->getErrors() as $error)
                    Notify::pushArray($errors);

            }

            $this->back();

        });

        $actions->addPut('/update-title-folder-:id', function($req) {

            if ($this->permitions['editing-folders'] != 'on') {

                Notify::push('Нет привилегий для изменения папок.', 'err');
                $this->back();

            }

            $ar = Folder::find(['id' => $req['id']])->get();

            if($ar->isNew()){

                Notify::push('Папка не существует.', 'err');
                $this->back();

            }

            $old_title = $ar->title;
            $ar->title = $_POST['update-title'];

            if($ar->save()) {

                Notify::push('Название папки успешно изменено.', 'ok');
                \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                    '<b>'.Users::get()->login.'</b> переименовал папку "'.$old_title.'" в "'.$ar->title.'".',
                    'Файлы');

            }

            else{

                Notify::push('Ошибка изменения названия папки.', 'err');

                foreach($ar->getErrors() as $error)
                    Notify::pushArray($errors);

            }

            $this->back();

        });

        $actions->addDelete('/delete-file-:id', function($req) {

            if ($this->permitions['deleting-files'] != 'on') {

                Notify::push('Нет привилегий для удаления файлов.', 'err');
                $this->back();

            }

            $file = File::find(['id' => $req['id']])->get();

            if(!$file->isNew()){

                $result = $file->delete();
                if ($result) {

                    Notify::push('Файл успешно удален.', 'ok');
                    \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                        '<b>'.Users::get()->login.'</b> удалил файл "'.$file->title.'".',
                        'Файлы');

                }
                else Notify::push('Ошибка удаления файла.', 'err');

            }
            else Notify::push('Файл не существует.', 'err');

            $this->back();

        });

        $actions->addDelete('/delete-folder-:id', function($req) {

            if ($this->permitions['deleting-folders'] != 'on') {

                Notify::push('Нет привилегий для удаления папок.', 'err');
                $this->back();

            }

            $folder = Folder::find(['id' => $req['id']])->get();

            if($folder->isNew()) Notify::push('Папка не существует.', 'err');

            elseif($folder->delete()) {

                Notify::push('Папка и её содержимое успешно удалены.', 'ok');
                \Model\Journal::add(\Model\Journal::STATUS_NOTICE,
                    '<b>'.Users::get()->login.'</b> удалил папку "'.$folder->title.'".',
                    'Файлы');

            }
            else Notify::push('Произошла ошибка при удалении папки.', 'err');

            $this->back();

        });

        $actions->addGet('/files/:id/href', function($req) {

            $id = $req['id'];

            $file = File::find(['id' => $id])
                ->get();

            if(!$file->isNew()) {

                if($file->isPicture()) {

                    $vres = isset($_GET['vres']) ? $_GET['vres'] : 500;
                    $hres = isset($_GET['hres']) ? $_GET['hres'] : 500;
                    $hres = isset($_GET['res']) ? $_GET['res'] : $hres;
                    $vres = isset($_GET['res']) ? $_GET['res'] : $vres;

                    $res = Config::PREFIX_PATH.'/'.$file->getPicture($hres.'x'.$vres);

                } else {

                    $res = Config::PREFIX_PATH.'/'.$file->getName();

                }

            } else {

                $res = '';

            }

            $this->json($res);

        });

        $solution = clone $actions;

        $router->mount('/folder-:id', $solution);
        $router->mount('/', $actions);

        $next();

    }

    public function getContent() {

        $permitions = $this->permitions;
        $files = $this->folder->files();
        $folders = $this->folder->folders();

        if ($permitions['self-only'] == 'on') {

            $files->andWhere(['user' => Users::get()->login]);
            $folders->andWhere(['user' => Users::get()->login]);

        }

        $data = [];
        $data['files'] = $files;
        $data['folders'] = $folders;

        $this->pager = Pager::init()
            ->pagesQb($files, self::ITEMS_ON_PAGE, $this->page)
            ->href($this->url.'/folder-'.$this->folder->id.'/?page=:page')
            ->get([
                'layout' => __DIR__.'/temp/pager/layout.tpl',
                'item'   => __DIR__.'/temp/pager/item.tpl',
            ]);

        return $this->render(__DIR__.'/temp/default.tpl', $data);

    }

    public function getSettingsContent($name = '', Array $permitions) {

        return $this->render(__DIR__.'/temp/settings.tpl');

    }

}
