<?php

namespace Back\Pages;

use Websm\Framework\Notify;
use Websm\Framework\Sort as NewSort;
use Websm\Framework\Chpu;
use Websm\Framework\Cyr2Lat;
use Websm\Framework\Router\Router;
use Model\Journal;
use Core\Users;
use Core\ModuleInterface;
use Core\Misc\Path;
use Core\Misc\NewPath\NewPath;
use Core\Misc\Pager\Pager;

class Pages extends \Websm\Framework\Response implements ModuleInterface
{
    public $permitions = [
        'creating' => 'off',
        'deleting' => 'off',
        'editing' => 'off',
        'presentation' => 'off',
        'chroot' => null,
    ];

    protected $model;
    protected $page = null;
    protected $pageObject = null;
    protected $path;
    protected $parent = null;
    protected $update;
    protected $url = 'pages';
    protected $title = 'Страницы';

    protected $pageNumber = 1;

    protected $leftItems = [];
    protected $pages = [];
    protected $pager;

    public function __construct(&$props = [])
    {
    }

    public function setSettings(array &$props = [])
    {
        $this->permitions = array_merge($this->permitions, $props);
        $this->permitions['chroot'] = $this->permitions['chroot'] ?: null;
    }

    public function getSettings()
    {
        return $this->permitions;
    }

    public function init($req, $next)
    {
        $this->css = [
            'plugins/tabs.js/public/default.min.css',
            'css/pages.css',
            'css/filesMin.css'
        ];

        $this->js = [
            'plugins/tabs.js/public/tabs.min.js',
            'plugins/ckeditor/ckeditor.js',
            'plugins/corrector.js/corrector.min.js',
            'plugins/limit-chars.js/limit-chars.min.js',
            'js/pages.js',
            'js/filesMin.js'
        ];

        $router = Router::instance();

        $router->addAll('/', function ($req, $next) {

            if (!empty($_GET['page-number'])) {

                $this->pageNumber = (int) $_GET['page-number'];
                if (empty($this->pageNumber)) $this->pageNumber = 1;
            }

            $next();
        }, ['end' => false]);

        $router->addAll('/(page-:id)?', function ($req, $next) {

            $chroot = $this->permitions['chroot'];
            $this->page = $req['id'] ?: $chroot;
            $page = \Model\Page::find(['id' => $this->page])->get();

            if ($chroot && !$page->belongs($chroot)) {

                $this->page = $chroot;
                $page = \Model\Page::find(['id' => $this->page])->get();
            }

            $this->pageObject = $page;
            $this->parent = $page->cid;

            $next();
        }, ['end' => false])->where(['id' => '\w+']);

        $actions = Router::group();


        /* $actions = $router->addAll('/(page-:id)?', function($req, $next) { */


        /*  $this->page = $req['id'] ?: null ; */
        /*  $this->parent = \Model\Page::find(['id' => $this->page]) */
        /*      ->get()->cid; */

        /*  $next(); */

        /* }, ['end' => false])->where(['id' => '\w+']); */

        $actions->addGet('/update-page-:id', function ($req, $next) {

            $this->update = $req['id'];
            /* $next(); */
        }, ['end' => false]);

        $actions->addGet('/update-cid/id-:id/cid-:cid', function ($req) {

            if ($this->permitions['editing'] != 'on') {

                Notify::push('Нет полномочий для изменеия.', 'err');
                $this->back();
            }

            $ar = \Model\Page::find(['id' => $req['id']])->get();

            $ar->cid = $req['cid'] ?: null;
            $ar->sort = 0;

            $ar->save()
                ? Notify::push('Страница успешно перемещена.', 'ok')
                : Notify::push('Ошибка перемещения страницы.', 'err');

            $sort = NewSort::init($ar);
            $sort->in($req['cid'])->normalise();
            $sort->in($this->page)->normalise();

            $this->back();
        })->where(['id' => '\w+', 'cid' => '\w+']);
        /* })->where(['id' => '\w+', 'cid' => '\w+'])->reverse(); */

        $actions->addPost('/create-page', function ($req) {

            if ($this->permitions['creating'] != 'on') {

                Notify::push('Нет полномочий для создания.', 'err');
                $this->back();
            }

            $ar = new \Model\Page;
            $aliases = [
                'id' => 'id',
                'cid' => 'cid',
                'chpu' => 'chpu',
                'title' => 'title',
            ];

            $ar->scenario('create');
            $ar->bind($_POST['create']);
            $ar->id = md5(uniqid());
            $ar->cid = $this->page;
            $ar->hash = md5($ar->title);

            if(isset($this->pageObject) && $this->pageObject->core_page)
            {
                $ar->chpu = '/' . Chpu::build('',$ar->title);
            }else{
                        
                Chpu::inject($ar);
                $ar->chpu = '/' . $ar->chpu;
            }

            if ($ar->save()) {

                Notify::push('Страница успешно создана.', 'ok');
                Journal::add(
                    Journal::STATUS_NOTICE,
                    '<b>' . Users::get()->login . '</b> создал страницу "/' . $ar->chpu . '".',
                    'Страницы'
                );
            } else {

                Notify::push('Ошибка создания страницы.', 'err');
                foreach ($ar->getErrors() as $errors)
                    Notify::pushArray($errors);
            }

            NewSort::init($ar)->normalise();

            $this->back();
        });

        $actions->addPut('/update-page-:id', function ($req) {

            if ($this->permitions['editing'] != 'on') {

                Notify::push('Нет полномочий для изменения.', 'err');
                $this->back();
            }

            $ar = \Model\Page::find(['id' => $req['id']])
                ->get();

            $ar->scenario('update');

            if ($ar->isNewRecord()) $this->back();
            
            $ar->tags = [];
            $ar->bind($_POST['update']);
            
            $ar->core_page = isset($_POST['core-page']) ? '1' : '0';

            $tags = &$_POST['new-tags'];
            $tags = explode(',', $tags);
            $ar->addTags($tags);

            if ($ar->save()) {

                Notify::push('Страница успешно изменена.', 'ok');
                Journal::add(
                    Journal::STATUS_NOTICE,
                    '<b>' . Users::get()->login . '</b> изменил содержимое "/' . $ar->chpu . '".',
                    'Страницы'
                );
            } else {
                Notify::push('Ошибка изменения страницы.', 'err');
                foreach ($ar->getErrors() as $errors)
                    Notify::pushArray($errors);
            }

            $this->back();
        });

        $actions->addPut('/clear-picture/:id', function ($req) {

            if ($this->permitions['editing'] != 'on') {

                Notify::push('Нет полномочий для изменения.', 'err');
                $this->back();
            }

            $ar = \Model\Page::find(['id' => $req['id']])->get();
            $ar->picture = null;

            if ($ar->isNew()) {
                Notify::push('Запись не существует', 'err');
                die();
            }

            if ($ar->save()) {

                Notify::push('Изображение сброшено.', 'ok');
                Journal::add(
                    Journal::STATUS_NOTICE,
                    '<b>' . Users::get()->login . '</b> сбросил изображение "/' . $ar->chpu . '".',
                    'Страницы'
                );
            } else Notify::push('Ошибка сброса изображения.', 'err');
        });

        $actions->addPut('/update-sort-:id', function ($req) {

            if ($this->permitions['editing'] != 'on') {

                Notify::push('Нет полномочий для изменения.', 'err');
                $this->back();
            }

            $ar = \Model\Page::find(['id' => $req['id']])->get();

            NewSort::init($ar)
                ->move($_POST['sort'])
                ->normalise();

            Journal::add(
                Journal::STATUS_NOTICE,
                '<b>' . Users::get()->login . '</b> изменил порядок для страницы "/' . $ar->chpu . '".',
                'Страницы'
            );

            $this->back();
        });

        $actions->addPut('/update-visibility-:id', function ($req) {

            if ($this->permitions['presentation'] != 'on') {

                Notify::push('Нет полномочий для изменения.', 'err');
                $this->back();
            }

            $ar = \Model\Page::find(['id' => $req['id']])
                ->get();

            $ar->scenario('visibility');
            if ($ar->isNewRecord()) $this->back();
            $ar->visible = $ar->visible ? 0 : 1;

            if ($ar->save()) {

                Notify::push('Видимость страницы успешно изменена.', 'ok');
                Journal::add(
                    Journal::STATUS_NOTICE,
                    '<b>' . Users::get()->login . '</b> ' . ($ar->visible ? 'отобразил' : 'скрыл') . ' страницу "/' . $ar->chpu . '".',
                    'Страницы'
                );
            } else {
                Notify::push('Ошибка изменения видимости страницы.', 'err');
                foreach ($ar->getErrors() as $errors)
                    Notify::pushArray($errors);
            }

            $this->back();
        });

        $actions->addDelete('/delete-tag', function ($req) {

            $res = $this->message;

            $tagId = &$_POST['tag_id'];

            $tag = \Model\Tags\Tags::find(['id' => $tagId])->get();

            if ($tag->isNew()) {

                Notify::push('Тэг не найден.', 'err');
                $res['notify'] = Notify::shiftAll();
                $res['status'] = 'err';

                $this->json($res);
            } elseif ($tag->static) {

                Notify::push('Тэг заблокирован от удаления.', 'err');
                $res['notify'] = Notify::shiftAll();
                $res['status'] = 'err';

                $this->json($res);
            }

            $tag->delete();
            $res['notify'] = Notify::shiftAll();

            $this->json($res);
        })->withAjax();

        $actions->addDelete('/delete-page-:id', function ($req) {

            if ($this->permitions['deleting'] != 'on') {

                Notify::push('Нет полномочий для удаления.', 'err');
                $this->back();
            }

            $ar = \Model\Page::find(['id' => $req['id']])->get();

            if ($ar->delete()) {

                Notify::push('Страница успешно удалена.', 'ok');
                Journal::add(
                    Journal::STATUS_NOTICE,
                    '<b>' . Users::get()->login . '</b> удалил страницу "/' . $ar->chpu . '".',
                    'Страницы'
                );
            } else Notify::push('Ошибка удаления страницы.', 'err');

            NewSort::init($ar)->normalise();

            $this->back();
        });

        /* $router->addAll('/parser', function($req) { */

        /*     ThtkParserTextPages::getPages(); */
        /*     die(); */

        /* }); */

        $solution = clone $actions;
        $router->mount('/page-:id', $actions);
        $router->mount('/', $solution);

        $api = new API\Controller;
        $router->mount('/api', $api->getRoutes());

        $next();

        $this->genContent();
    }

    public function genContent()
    {
        $pathTemplats = [
            'layout' => __DIR__ . '/Path/temp/layout.tpl',
            'item'   => __DIR__ . '/Path/temp/item.tpl'
        ];

        $ar = \Model\Page::find(['id' => $this->page])
            ->get();

        $newPath = NewPath::init($ar);
        $newPath->setTemplates($pathTemplats);
        $this->newPath = $newPath->getHtml();

        $qb = \Model\Page::find(['cid' => $this->page])
            ->order(['sort ASC']);

        $pagerTemplate = [
            'layout' => __DIR__ . '/Pager/temp/layout.tpl',
            'item'   => __DIR__ . '/Pager/temp/items.tpl',
        ];

        $pagerHref = $this->url;
        if ($this->page) $pagerHref .= '/page-' . $this->page;
        $pagerHref .= '/?page-number=:page';

        $newPager = Pager::init()->pagesQb($qb, 20, $this->pageNumber)
            ->href($pagerHref);

        $this->pager = $newPager->get($pagerTemplate);

        $this->pages = $qb->genAll();

        $this->leftItems = \Model\Page::find(['cid' => $this->parent])
            ->order(['sort ASC'])
            ->genAll();
    }

    public function getContent()
    {
        return $this->render(__DIR__ . '/temp/default.tpl');
    }

    public function getSettingsContent($name = '', array $permitions)
    {
        return $this->render(__DIR__ . '/temp/settings.tpl', $permitions);
    }
}
