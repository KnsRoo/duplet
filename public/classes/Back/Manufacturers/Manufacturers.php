<?php

namespace Back\Manufacturers;

use \Websm\Framework\Notify;
use \Websm\Framework\Response;
use \Websm\Framework\Router\Router;

use \Core\Users;
use \Core\Misc\NewPath\NewPath;
use \Websm\Framework\Sort as NewSort;
use \Core\Misc\Pager\Pager;
use Core\ModuleInterface;

use Back\Files\File;
use Back\Manufacturers\Models\Manufacturer;

use Model\Journal as JournalModel;

class Manufacturers extends Response implements ModuleInterface {

    private $link;

    protected $folder;
    protected $file;
    protected $path;
    protected $parent;
    protected $url = 'manufacturers';
    protected $title = 'Производители';
    protected $page = 1;

    protected $folders = [];
    protected $files = [];

    public $permitions = [];

    public function setSettings(Array &$props = []) {}

    public function getSettings() { return $this->permitions; }

    public function init($req, $next) {

        $this->css = [
            'css/manufacturers.css',
            'css/filesMin.css'
        ];

        $this->js = [
            'js/manufacturers.js',
            'js/filesMin.js'
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

        $actions = Router::group();

        $actions->addGet('/update-manufacturer-:id', function($req, $next) {

            $this->update = $req['id'];
            /* $next(); */

        }, ['end' => false]);

        $actions->addPost('/create-manufacturer', function($req) {

            $ar = new Manufacturer();

            $ar->scenario('create');
            $ar->bind($_POST['create']);
            $ar->id = md5(uniqid());

            if($ar->save()) {

                Notify::push('Производитель успешно добавлен.', 'ok');
                JournalModel::add(JournalModel::STATUS_NOTICE,
                    '<b>'.Users::get()->login.'</b> добавил производителя "'.$ar->name.'".',
                    'Производители');

            }

            else {

                Notify::push('Ошибка создания производителя', 'err');

                foreach($ar->getErrors() as $errors)
                    Notify::pushArray($errors);

            }

            NewSort::init($ar)->normalise();

            $this->back();

        });

        $actions->addPut('/update-manufacturer-:id', function($req) {

            $manufacturer = Manufacturer::find(['id' => $req['id']])->get();


            if($manufacturer->isNew()){

                Notify::push('Производитель не найден.', 'err');
                $this->back();

            }

            $manufacturer->scenario('update');

            $manufacturer->bind($_POST['update']);

            if($manufacturer->save()) {

                Notify::push('Производитель успешно изменен.', 'ok');
                JournalModel::add(JournalModel::STATUS_NOTICE,
                    '<b>'.Users::get()->login.'</b> изменил информацию о производителе "'.$manufacturer->name.'".',
                    'Производители');

            } else{

                Notify::push('Ошибка изменения производителя.', 'err');

                foreach($manufacturer->getErrors() as $error)
                    Notify::pushArray($error);

            }

            $this->back();

        });

        $actions->addPut('/update-sort-:id', function($req) {

            $ar = Manufacturer::find(['id' => $req['id']])->get();

            NewSort::init($ar)
                ->move($_POST['sort'])
                ->normalise();

            JournalModel::add(JournalModel::STATUS_NOTICE,
                '<b>'.Users::get()->login.'</b> изменил порядок для производителя "'.$ar->name.'".',
                'Производители');

            $this->back();

        });

        $actions->addPut('/clear-picture/:id', function($req) {

            $ar = Manufacturer::find(['id' => $req['id']])->get();
            $ar->picture = null;

            if($ar->isNew()) {
                Notify::push('Производитель не найден', 'err');
                die();
            }

            if($ar->save()) {

                Notify::push('Изображение сброшено.', 'ok');
                JournalModel::add(JournalModel::STATUS_NOTICE,
                    '<b>'.Users::get()->login.'</b> сбросил изображение у производителя "'.$ar->name.'".',
                    'Страницы');

            }
            else Notify::push('Ошибка сброса изображения.', 'err');

        });

        $actions->addDelete('/delete-:id', function($req) {

            $manufacturer = Manufacturer::find(['id' => $req['id']])->get();

            if($manufacturer->isNew()) Notify::push('Производитель не найден.', 'err');

            elseif($manufacturer->delete()) {

                Notify::push('Производитель успешно удален.', 'ok');
                JournalModel::add(JournalModel::STATUS_NOTICE,
                    '<b>'.Users::get()->login.'</b> удалил производителя "'.$manufacturer->name.'".',
                    'Производители');

            }
            else Notify::push('Произошла ошибка при удалении.', 'err');

            NewSort::init($manufacturer)->normalise();

            $this->back();

        });

        $router->mount('/', $actions);

        $next();

    }

    public function getContent() {

        $permitions = $this->permitions;
        $qb = Manufacturer::find()->order('sort');

        $this->pager = Pager::init()
            ->pagesQb($qb, 20, $this->page)
            ->href($this->url.'/?page=:page')
            ->get([
                'layout' => __DIR__.'/temp/pager/layout.tpl',
                'item'   => __DIR__.'/temp/pager/item.tpl',
            ]);

        $manufacturers = $qb->genAll();

        $data = [];
        $data['manufacturers'] = $manufacturers;

        return $this->render(__DIR__.'/temp/default.tpl', $data);

    }

    public function getSettingsContent($name = '', Array $permitions) {

        return $this->render(__DIR__.'/temp/settings.tpl');

    }

}
