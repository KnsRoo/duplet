<?php

namespace Websm\Framework\UserSession;

use Websm\Framework\Session\StorageInterface;

interface UserInterface {

    public function __get($key);

    public function __set($key, $value);

    public function initEngine($engine);

    public function authEngine($engine);

    public function isAuth();

    public function isAuthEngine($engine);

    public function deAuth();

    public function deAuthEngine($engine);

    public function getZone();

    public function getData();

    public function bind(Array $data);

}
