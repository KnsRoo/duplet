<?php

namespace Websm\Framework\UserSession;

use Websm\Framework\Session\StorageInterface;

class User implements UserInterface {

    const STATUS_KEY = 'auth_status';
    const DATA_KEY = 'auth_data';

    private $session;
    private $zone;
    private $auth = [];
    private $data = [];

    public function __construct(StorageInterface $session, $zone = 'default') {

        $this->session = $session;
        $this->zone = $zone;

        $this->restore(self::STATUS_KEY, $this->auth);
        $this->restore(self::DATA_KEY, $this->data);

    }

    public function __destruct() {

        $this->save(self::STATUS_KEY, $this->auth);
        $this->save(self::DATA_KEY, $this->data);

    }

    public function __get($key) {

        $data = &$this->data[$key];
        return $data;

    }

    public function __set($key, $value) {

        $this->data[$key] = $value;

    }

    private function save($key, &$value) {

        $session = $this->session;
        $zone = $this->zone;
        $key = "${zone}::${key}";

        $session->set($key, serialize($value));

    }

    private function restore($key, &$place) {

        $session = $this->session;
        $zone = &$this->zone;
        $key = "${zone}::${key}";

        if ($session->has($key)) {

            $data = $session->get($key);
            $place = unserialize($data);

        }

    }

    public function initEngine($engine) {

        $status = &$this->auth[$engine];
        if (!$status) $status = false;

        return $this;

    }

    public function authEngine($engine) {

        $status = &$this->auth[$engine];
        $status = true;

        return $this;

    }

    public function isAuth() {

        if (!$this->auth) return false;

        foreach ($this->auth as $status) {

            if (!$status) return false;

        }

        return true;

    }

    public function isAuthEngine($engine) {

        $status = &$this->auth[$engine];

        if (!$status) return false;
        else return true;

    }

    public function deAuth() {

        foreach ($this->auth as &$status) {

            $status = false;

        }

        $this->data = [];

    }

    public function deAuthEngine($engine) {

        $status = &$this->auth[$engine];
        $status = false;

    }

    public function getZone() {

        return $this->zone;

    }

    public function getData() {

        return $this->data;

    }

    public function bind(Array $data) {

        $this->data = $data + $this->data ;

    }

}
