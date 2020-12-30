<?php

namespace Back\Layout;

use Core\Misc as Misc;
use Websm\Framework\Router\Router;
use Websm\Framework\Notify;
use Core\Users;
use Back\Users\Modules;
use Websm\Framework\Di;

class Layout extends \Websm\Framework\Response {

    private $link;
    public $content = '';
    public $modules = '';
    public $menu = '';
    public $systemMenu = '';

    public function __construct() { }

    public function getRoutes() {

        $group = Router::group();

        $group->addAll('/:module?', [$this, 'initModuleWithAjax'], ['end' => false])
            ->withAjax();

        $group->addAll('/:module?', [$this, 'initModule'], ['end' => false])
            ->withNotAjax();

        return $group;

    }

    public function initModuleWithAjax($req, $next) {

        $user = Users::get();
        $router = Router::instance()->getParent();
        $modules = new Modules;

        $di = Di\Container::instance();
        $di->add('Modules', $modules);

        foreach ($user->modules as $name => $info)
            $modules->add($name, $info);

        if (!$req['module'] || !$modules->exists($req['module'])) {

            Notify::push('Модуль не найден или у вас нет на него прав.', 'err');
            $this->notify = Notify::shiftAll();
            $this->json();
            die();

        }
        else $module = $modules->getByName($req['module']);

        $object = $module->getObject();

        $router->addAll(['/'.$req['module'], 'init'], [$object, 'init'], ['end' => false]);

        $next();

        $this->__set('content', $object->getContent());

        $this->notify = Notify::shiftAll();
        $this->json();
        die();

    }

    public function initModule($req, $next) {

        $user = Users::get();
        $router = Router::instance()->getParent();
        $modules = new Modules;

        $di = Di\Container::instance();
        $di->add('Modules', $modules);


        foreach ($user->modules as $name => $info)
            $modules->add($name, $info);

        if (!$req['module'] || !$modules->exists($req['module'])) {

            $module = $modules->getCurrent();
            $this->location('/admin/'.strtolower($module->getName()));

        }
        else $module = $modules->getByName($req['module']);

        $object = $module->getObject();

        $router->addAll('/'.$req['module'], [$object, 'init'], ['end' => false]);

        $next();

        $this->content = $object->getContent();

        foreach ($modules->getAll() as $name => $item) {

            if ($item->isHidden() || $item->isDisabled()) continue;

            $data = [];
            $data['module'] = $item;
            $data['active'] = ($module == $item);

            if ($item->isSystem())
                $this->systemMenu .= $this->render(__DIR__.'/temp/menu_item.tpl', $data);

            else $this->menu .= $this->render(__DIR__.'/temp/menu_item.tpl', $data);

        }

        die($this->render(__DIR__.'/temp/default.tpl'));

    }

}
