<?php

namespace Back\FilesMin;

use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Core\ModuleInterface;
use Core\Misc\NewPath\NewPath;
use Core\Misc\Notify;
use Back\Files\Config;
use Model\FileMan\Folder;
use Model\FileMan\File;
use Model\Journal;
use Core\Users;
use Websm\Framework\Di;

class FilesMin extends Response implements ModuleInterface {

    public $permitions = [
        'status' => 'off',
    ];

    public $options;
    public $uniqid = '';
    public $href = 'filesmin';
    public $path = '';
    public $title = 'Корень';
    public $cid = '';
    public $currentPage = 1;
    public $itemsOnPage = 20;
    public $allItems = 0;

    public function __construct() {

        $this->uniqid = uniqid();

    }

    public function init($req, $next) {

        $this->css = [
            'css/filesMin.css'
        ];

        $this->js = [
            'js/filesMin.js'
        ];

        $router = Router::instance();

        $router->addAll('/(:folder)?', function($req, $next) {

            if ($this->permitions['status'] != 'on') {

                $next();
                return;

            }

            $this->options = new Options();
            $this->options->bind($_REQUEST);
            $this->options->validate();
            $this->currentPage = &$_REQUEST['page'];

            $next();

        });

        $actions = $router->addAll('/(:folder)?', function($req, $next) {

            if ($this->permitions['status'] != 'on') {

                $next();
                return;

            }

            $chroot = $this->permitions['chroot'];
            $folderId = $req['folder'] ?: $chroot;

            $folder = Folder::find(['id' => $folderId])->get();

            if (!$folder->belongs($chroot))
                $this->folder = Folder::find(['id' => $chroot])->get();

            else $this->folder = $folder;

            $this->path = NewPath::init($this->folder)
                ->setTemplates([
                    'item' => __DIR__.'/temp/path/item.tpl',
                    'layout' => __DIR__.'/temp/path/layout.tpl',
                ])
                ->setData([ 'url' => $this->href ])
                ->getHtml();

            $next();

        });

        $actions->addPost('/', function() {

            if ($this->permitions['status'] != 'on') return;

            if ($this->permitions['uploading-files'] != 'on') {

                Notify::push('Нет привилегий для загрузки файлов.', 'err');
                return;

            }

            $folder = $this->folder;

            foreach ($_FILES['files']['name'] as $key => $name) {

                $file = $folder->createFile($_FILES['files']['tmp_name'][$key], $name);

                if ($file) {

                    Journal::add(Journal::STATUS_NOTICE,
                        '<b>'.Users::get()->login.'</b> загрузил файл "'.$file->title.'".',
                        'Файлы');

                }

                foreach($folder->getErrors() as $error)
                    Notify::pushArray($error);

            }

        }, ['end' => false])->withAjax();

        $next();

    }

    public function getContent() {

        if ($this->permitions['status'] != 'on')
            return $this->render(__DIR__.'/temp/off.tpl');

        $permitions = $this->permitions;
        $types = $this->options->getTypes();
        $files = $this->folder->getFiles();
        $folders = $this->folder->folders();

        if($types) $files->andWhere(['ext' => $types]);

        if ($permitions['self-only'] == 'on') {

            $files->andWhere(['user' => Users::get()->login]);
            $folders->andWhere(['user' => Users::get()->login]);

        }
        $this->allItems = $files->Count();

        $offset = ($this->currentPage - 1) * $this->itemsOnPage;
        $files->limit([$offset,  $this->itemsOnPage]);

        $data = [];
        $data['files'] = $files->getAll();
        $data['folders'] = $folders->getAll();

        return $this->render(__DIR__.'/temp/default.tpl', $data);

    }

    public function setSettings(Array &$props = []) {

        $di = Di\Container::instance();
        $modules = $di->get('Modules');

        $files = $modules->getByName('Files');
        if ($files) {

            $this->permitions = array_merge($this->permitions, $files->getSettings());

        }

        $this->permitions = array_merge($this->permitions, $props);

    }

    public function getSettings() { return $this->permitions; }

    public function getSettingsContent($name = '', Array $permitions) {

        return $this->render(__DIR__.'/temp/settings.tpl');

    }

}
