<?php

namespace Model\Interview;

use Websm\Framework\Db\ActiveRecord;

Class Journal extends ActiveRecord {

    public static $table = 'interview_journal';

    public $id;
    public $quiz_id;
    public $ip;

    public function getRules() {

        return [

            ['quiz_id', 'required'],

            ['ip', 'insertIp'],
            ['ip', 'required'],
            ['ip', 'validateIp'],

        ];

    }

    public function insertIp($ip = null) {

        $ip = &$this->$ip;

        $ip = self::getIp();

    }

    public function validateIp ($ip = null) {

        $ip = &$this->$ip ?: $this->ip;
        $flags = FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6;

        if (!filter_var($ip, FILTER_VALIDATE_IP, $flags))
            return false;

        return true;
    }

    public static function getIp() {

        $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR'])
            ? $_SERVER['HTTP_X_FORWARDED_FOR']
            : $_SERVER['REMOTE_ADDR'];

        return $ip;

    }
}
