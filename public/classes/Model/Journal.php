<?php

namespace Model;

use Websm\Framework\Db\ActiveRecord;

class Journal extends ActiveRecord {

    const STATUS_NOTICE = 'notice';
    const STATUS_ERROR = 'error';
    const STATUS_WARNING = 'warning';

    public static $table = 'journal';

    public $message = '';
    public $module = '';
    public $status = '';
    public $ip = '';

    public function getRules() {

        return [
            ['message', 'pass'],
            ['module', 'pass'],
            ['status', 'pass'],
            ['ip', 'pass'],
        ];

    }

    public function getDate($format = 'd.m.Y') {

        return (new \DateTime($this->date))
            ->format($format);

    }

    public static function add($status, $message = '', $module = 'Undef', $ip = null) {

        $remoteAddr = isset($_SERVER['HTTP_X_FORWARDED_FOR'])
            ? $_SERVER['HTTP_X_FORWARDED_FOR']
            : $_SERVER['REMOTE_ADDR'];

        $ip = $ip ?: $remoteAddr;

        $journal = new self;
        $journal->status = $status;
        $journal->message = $message;
        $journal->module = $module;
        $journal->ip = $ip;

        return $journal->save();

    }

}
