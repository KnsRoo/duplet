<?php

namespace Websm\Framework\Db;

class Connection extends \PDO{

    protected $_schema = [
        'dsn' => null,
        'username' => null,
        'password' => null,
    ];

    protected $_type = 'mysql';

    public function __construct($params){

        $params = array_merge($this->_schema, $params);

        if(preg_match('/^(?<type>.+)\:/U', $params['dsn'], $matches))
            $this->_type = $matches['type'];

        parent::__construct($params['dsn'], $params['username'], $params['password']);

    }

    public function type(){

        return $this->_type;

    }

}
