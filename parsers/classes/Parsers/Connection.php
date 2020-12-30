<?php

namespace Parsers;

use PDO;
use PDOException;
use Websm\Framework\Db\Config;

class Connection 
{
    private static $pdo;

    public function getPdo() : PDO
    {
        if (!isset(self::$pdo)) 
        {
            $this->createPdo();
        }
        return self::$pdo;
    }

    private function createPdo() 
    {
        try {
            $dsn = 'mysql:host=m.0.mysql.websm.io;port=3306;dbname=db_duplet.shop;charset=utf8';
            $username = 'duplet_admin';
            $password = '8hj9ypHcAA28Q4ZxKqZwWj2P5D8yaKRh';
            self::$pdo = new PDO($dsn, $username, $password, [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
        } catch (PDOException $e) {
            print_r("Error:\n");
            print_r($e->getMessage()."\n");
            print_r("Trace:\n");
            print_r($e->getTraceAsString());
        }
    }
}
