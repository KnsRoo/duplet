<?php

namespace Back\Mailing;

use Core\Response;
use Core\ModuleInterface;
use Core\Router\Router;
use Core\Misc\Notify;

class Mailing extends Response implements ModuleInterface {

    private $content;

    public function __construct() {

    }

    public function setSettings(Array &$props = []) {
    
    }

    public function getSettings() {
    
    }

    public function getSettingsContent($name = '', Array $permitions) {
    
    }

    public function getRoutes() {

        $group = Router::group();

        $group->addGet('/', [$this, 'defaultAction'])
            ->setName('Mailing.defaultAction');

        return $group;

    }

    public function init($req, $next) {

        $router = Router::instance();

        $router->mount('/', $this->getRoutes());

        $next();

    }

    public function defaultAction() {

        $data = [];
        $html = $this->render(__DIR__.'/temp/default.tpl', $data);

        $this->content = $html;


    }

    public function getContent() {

        return $this->content;

    }

}
