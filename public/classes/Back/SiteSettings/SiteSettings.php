<?php

namespace Back\SiteSettings;

use Websm\Framework\Router\Router;
use Websm\Framework\Response;

use Core\ModuleInterface;
use Websm\Framework\Notify;
use Core\Users;

class SiteSettings extends Response implements ModuleInterface {

    const TEMPLATES = __DIR__ . '/temp/';

    protected $url = 'settings/sitesettings';
    protected $title = 'Настройки сайта';
    protected $data = [];

    public function __construct(&$props = []) { }

    public function setSettings(Array &$props = []) { }

    public function getSettings() { return []; }

    public function getRoutes() {

        $group = Router::group();

        $api = new API\V1\Controller;

        $group->mount('/api/v1', $api->getRoutes());

        return $group;

    }

    public function init($req, $next) {

        $this->css = [];

        $this->js = array_merge($this->js, [
            'js/site-settings.js',
        ]);

        $router = Router::instance();

        $router->mount('/', $this->getRoutes());

        $next();

    }

    public function getContent() {

        return $this->render(__DIR__ . '/temp/default.tpl', []);
    }

    public function getSettingsContent($name = '', Array $permitions) {}

}
