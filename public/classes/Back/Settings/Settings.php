<?php

namespace Back\Settings;

use Websm\Framework\Router\Router;
use Back\Layout\LayoutModel;
use Back\Users\Module;
use Back\Users\Modules;
use Core\Users;
use Core\ModuleInterface;

class Settings extends \Websm\Framework\Response implements ModuleInterface {

    private $link;

    protected $nameSubModule = '';
    protected $content = '';
    protected $subModules = [];
    protected $url = 'settings';
    protected $title = 'Настройки';

    public function __construct(&$props = []) { }

    public function setSettings(Array &$props = []) { }

    public function getSettings() { return []; }

    public function getRoutes() {

        $group = Router::instance();

        $group->addAll('/', [$this, 'baseAction'], ['end' => false]);

        return $group;

    }

    public function init__($req, $next) {

        /***
        * Pushing $resp
        */
        $this->css = [
            'css/settings.css'
        ];

        $this->js = [
            'js/settings.js'
        ];

        $route = Router::instance();

        $route->mount('/', $this->getRoutes());
        $next();

    }

    public function init($req, $next) {
    /* public function baseAction($req, $next) { */

        /***
        * Pushing $resp
        */
        $this->css = [
            'css/settings.css'
        ];

        $this->js = [
            'js/settings.js'
        ];

        $user = Users::get();
        $modules = new Modules;

        foreach ($user->modules as $name => $info) {

            $module = new Module($name, $info);
            if ($module->isSetting()) $modules->addModule($module);

        }

        if ($modules->isEmpty()) return false;

        $this->subModules = $modules;

        $route = Router::instance();

        $route->addAll('/:module?', function($req, $next) use ($modules) {

            $route = Router::instance()->getParent();

            if (!$req['module'] || !$modules->exists($req['module'])) {

                $module = $modules->getCurrent();
                $this->location('/admin/settings/'.strtolower($module->getName()));

            }

            else $module = $modules->getByName($req['module']);
            $this->nameSubModule = $module->getName();

            $object = $module->getObject();
            $route->addAll('/'.$req['module'], [$object, 'init'], ['end' => false]);

            $next();

            $this->content = $object->getContent();

        }, ['end' => false]);

        $next();

    }

    public function _init($req, $next) {

        /***
        * Pushing $resp
        */
        $this->css = [
            'css/settings.css'
        ];

        $this->js = [
            'js/settings.js'
        ];

        $router = Router::instance();

        $allModules = LayoutModel::getModules();
        $moduleName = LayoutModel::getModuleName($this);
        $module = &LayoutModel::getModuleByName($moduleName);
        $this->subModules = $module['dependencies'];

        foreach ($this->subModules as $key => &$moduleName) {

            $moduleName = strtolower($moduleName);

            if (!isset($allModules[$moduleName])
                || $allModules[$moduleName]['disabled'] == true)

                unset($this->subModules[$key]);

        }


        if (count($this->subModules) > 0) {

            $router->addAll('/:module?', function($req, $next) {

                $router = Router::instance()->getParent();

                reset($this->subModules);
                $this->nameSubModule = $req['module'] ?: current($this->subModules);

                if (array_search($this->nameSubModule, $this->subModules) !== false) {

                    $data = &LayoutModel::getModuleByName($this->nameSubModule);
                    $subModule = LayoutModel::initModule($data);

                    $router->addAll('/'.$req['module'], [$subModule, 'init'], ['end' => false]);
                    $next();

                    $this->content = $subModule->getContent();

                }

            }, ['end' => false]);

        }

        $next();

    }

    public function getContent() {

        return $this->render(__DIR__.'/temp/default.tpl');

    }

    public function getSettingsContent($name = '', Array $permitions) {}

}
