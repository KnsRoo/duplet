<?php

namespace Websm\Framework\Locker;

use Websm\Framework\NoSQL\NoSQLInterface;

class Service implements LockerInterface {

    const ALLOWED_ATTEMPTS = 3;
    const DAY = 86400;
    const DEFAULT_LOCK_TIME = 300;

    private $db;
    private $id;

    public function __construct(NoSQLInterface $db, $id = null) {

        $this->db = $db;

        if (empty($id)) {

            $id = $_SERVER['HTTP_HOST'].':'.$_SERVER['REMOTE_ADDR'];

        }

        $this->id = $id;

    }

    public function isLocked() {

        return $this->db->exists($this->id.':lock');

    }

    public function setId($id) {

        $this->id = $id;

    }

    public function getLockTime() {

        $key = $this->id.':lock';

        if ($this->db->exists($key)) {

            return $this->db->getTimeout($key);

        }

        else return 0;

    }

    public function lock($time) {

        $key = $this->id.':lock';

        $this->db->set($key, $time);
        $this->db->setTimeout($key, $time);

    }

    public function clearState() {

        $id = $this->id;
        $this->db->delete($id.':attempts');
        $this->db->delete($id.':lock');

    }

    public function getAviableAttempts() {

        $key = $this->id.':attempts';
        $attempts = self::ALLOWED_ATTEMPTS;

        if ($this->db->exists($key)) {

            $attempts -= $this->db->get($key);
            $attempts = $attempts > 0 ? $attempts : 0;

        }

        return $attempts;

    }

    public function decAttempts() {

        $attempts = 1;
        $key = $this->id.':attempts';

        if ($this->db->exists($key)) {

            $attempts = $this->db->get($key);
            $this->db->set($key, ++$attempts);

        } else {

            $this->db->set($key, $attempts);
            $this->db->setTimeout($key, self::DAY);

        }

        if ($attempts > self::ALLOWED_ATTEMPTS) {

            $factor = $attempts - self::ALLOWED_ATTEMPTS;
            $this->lock($factor * self::DEFAULT_LOCK_TIME + rand(20, 140));

        }

    }

}
