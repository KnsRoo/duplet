<?php

namespace Back\Slides;

use \Model\FileMan\File;
use \Model\FileMan\Folder;
use Back\Files\Picture;
use Model\Journal as JournalModel;
use Back\Slides\Models\SlidesModel;

use Core\Db\Qb;
use Websm\Framework\Notify;
use Websm\Framework\Sort as NewSort;
use Websm\Framework\Chpu as NewChpu;
use Core\Misc\Path;
use Core\Misc\NewPath\NewPath;
use Core\Misc\Pager\Pager;
use Websm\Framework\Response;
use Websm\Framework\Router\Router;
use Core\ModuleInterface;
use Core\Users;

class Slides extends Response implements ModuleInterface {

    const TEMPLATES = __DIR__ . '/temp/';

    public $permitions = [
        'creating' => 'off',
        'deleting' => 'off',
        'editing' => 'off',
        'chroot' => null,
    ];

    protected $url = 'slides';
    protected $title = 'Главный слайдер';
    protected $slides = null;
    protected $model;
    protected $data = [];
    protected $path;
    protected $pager = '';
    protected $page = 1;
    protected $parent = null;

    public function __construct(&$props = []) { }

    public function setSettings(Array &$props = []) {

        $this->permitions = array_merge($this->permitions, $props);
        $this->permitions['chroot'] = $this->permitions['chroot'] ?: null;

    }

    public function getSettings() { return $this->permitions; }

    public function init($req, $next) {

        $this->css = [
            'css/slides.css',
            'css/filesMin.css'
        ];

        $this->js = array_merge($this->js, [
            'plugins/ckeditor/ckeditor.js',
            'js/slides.js',
            'js/filesMin.js',
        ]);

        $route = Router::instance();

        $route->mount('/', $this->getRoutes());

        $next();

    }

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/new-slide', [$this, 'newSlide']);

        $group->addPost('/create-slide', [$this, 'createSlide'])
            ->setName('Slides.createSlide');

        $group->addGet('/update-slide-:id', [$this, 'updateSlide']);

        $group->addGet('/update-image/id-:id/image-:imageId', [$this, 'updateImage']);

        $group->addPut('/update-slide-:id', [$this, 'putUpdateSlide'])
            ->setName('Slides.updateSlide');

        $group->addPut('/update-sort-slide-:id', [$this, 'putUpSortSlide']);

        $group->addPut('/update-visibility-slide-:id', [$this, 'putUpVisibility']);

        $group->addDelete('/delete-slide-:id', [$this, 'deleteSlide']);

        return $group;

    }

    public function newSlide() {

        $rCreateSlide = Router::byName('Slides.createSlide');
        $rDefault = Router::instance();

        $data = [];
        $data['rDefault'] = $rDefault;
        $data['rCreateSlide'] = $rCreateSlide;

        $this->content = $this->render(self::TEMPLATES . 'slideNew.tpl', $data);

    }

    public function createSlide() {

        $ar = new SlidesModel();

        $ar->scenario('create');
        $ar->bind($_POST['create']);
        $ar->id = md5(uniqid());
        /* NewChpu::inject($ar); */

        if ($ar->save()) {

            Notify::push('Слайд успешно создан.', 'ok');
            NewSort::init($ar)->normalise();

            JournalModel::add(JournalModel::STATUS_NOTICE,
                '<b>'.Users::get()->login.'</b> создал слайд "'.$ar->title.'".',
                'Слайд');

        } else {

            Notify::push('Ошибка создания слайда.', 'err');
            foreach($ar->getErrors() as $errors)
                Notify::pushArray($errors);

        }

        $this->back();

    }

    public function updateSlide($req) {

        $id = $req['id'];

        $slide = SlidesModel::find()
            ->where(['id' => $id])
            ->get();

        if (!$slide->isNewRecord()) {

            $rDefault = Router::instance();
            $rUpdateSlide = Router::byName('Slides.updateSlide');

            $picture = $slide->getPicture('1000x1000');
            $style = $picture ? 'background-image: url('.$picture.');' : '';

            $data = [
                'slide' => $slide,
                'picture' => '',
                'style' => $style,
                'rDefault' => $rDefault,
                'rUpdateSlide' => $rUpdateSlide,
            ];

            $this->content = $this->render(self::TEMPLATES . 'slideEdit.tpl', $data);

        }

    }

    public function updateImage($req) {

        $result = SlidesModel::find(['id' => $req['id']])
            ->update(['picture' => $req['imageId']])
            ->execute();

        $result
            ? Notify::push('Изображение у слайда успешно изменено.', 'ok')
            : Notify::push('Ошибка изменения изображения у слайда.', 'err');

        $this->back();

    }

    public function putUpdateSlide($req) {

        $ar = SlidesModel::find(['id' => $req['id']])
            ->get();

        $ar->scenario('update');
        if($ar->isNewRecord()) $this->back();
        $ar->bind($_POST['update']);
        isset($_POST['checked-mini-files']) && $ar->picture = $_POST['checked-mini-files'];
        if ($ar->save()) Notify::push('Слайд успешно изменен.', 'ok');
        else {
            Notify::push('Ошибка изменения слайда.', 'err');
            foreach($ar->getErrors() as $errors)
                Notify::pushArray($errors);
        }

        $this->back();

    }

    public function putUpSortSlide($req) {

        $ar = SlidesModel::find(['id' => $req['id']])->get();

        NewSort::init($ar)->move($_POST['sort'])
            ->normalise();

        $this->back();

    }

    public function putUpVisibility($req) {

        $ar = SlidesModel::find(['id' => $req['id']])
            ->get();

        $ar->scenario('visibility');
        if($ar->isNewRecord()) $this->back();
        $ar->visible = $ar->visible ? 0 : 1;
        echo '<pre>'.print_r($ar, true).'</pre>';
        if ($ar->save()) Notify::push('Видимость слайда успешно изменена.', 'ok');
        else {
            Notify::push('Ошибка изменения видимости слайда.', 'err');
            foreach($ar->getErrors() as $errors)
                Notify::pushArray($errors);
        }

        $this->back();

    }


    public function deleteSlide($req) {

        $ar = SlidesModel::find(['id' => $req['id']])->get();

        $ar->delete()
            ? Notify::push('Слайд успешно удален.', 'ok')
            : Notify::push('Ошибка удаления слайда.', 'err');

        NewSort::init($ar)->normalise();

        $this->back();

    }

    public function getContent() {

        $pathTemplats = [
            'layout' => __DIR__.'/Path/temp/layout.tpl',
            'item'   => __DIR__.'/Path/temp/item.tpl'
        ];

        $ar = SlidesModel::find(['id' => $this->slide])
            ->get();

        $newPath = NewPath::init($ar);
        $newPath->setTemplates($pathTemplats);
        $this->newPath = $newPath->getHtml();

        return $this->render(__DIR__.'/temp/default.tpl');

    }

    public function getSettingsContent($name = '', Array $permitions) {

        return $this->render(__DIR__.'/temp/settings.tpl', $permitions);

    }

}
