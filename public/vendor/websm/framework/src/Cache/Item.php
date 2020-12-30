<?php

namespace Websm\Framework\Cache;

use DateTime;
use DateInterval;

class Item implements CacheItemInterface {

    private $key;
    private $value;
    private $expiresAt = null;
    private $isHit = false;

    public function __construct($key) {

        $this->key = $key;

    }

    public function getKey() {

        return $this->key;

    }

    public function get() {

        if ($this->isHit()) return $this->value;

        return null;

    }

    public function isHit() {

        if (!$this->isHit) return false;

        elseif ($this->expiresAt === null) return true;

        $now = new DateTime;
        return $now < $this->expiresAt;

    }

    public function set($value) {

        $this->isHit = true;
        $this->value = $value;
        return $this;

    }

    public function expiresAt($expiration) {

        $this->expiresAt = $expiration;
        return $this;

    }

    public function expiresAfter($time) {

        if ($time instanceof DateInterval) {

            $now = new DateTime();
            $this->expiresAt = $now->add($time);

        } elseif (is_int($time)) {

            $seconds = date('U') + $time;
            $this->expiresAt = DateTime::createFromFormat('U', $seconds);

        } else $this->expiresAt = null;

        return $this;

    }

}
