<?php

namespace Websm\Framework\Db;
use Websm\Framework\Db\Config;

class Qb {

    protected $_db;
    protected $_table;
    protected $_class;

    protected $_command;
    protected $_insert;
    protected $_update = [];
    protected $_select = '*';
    protected $_join = ''; 
    protected $_where;
    protected $_group;
    protected $_order;
    protected $_limit;

    protected $_query = '';
    protected $_params = [];

    public $_error = [];

    public static function table($table = null){

        if(!$table || !is_string($table))
            throw new \Exception('Required first param type string.');

        $ret = new static;
        $ret->setTable($table);

        return $ret;

    }

    public static function query($sql = null, Array $params = []){

        if(!$sql || !is_string($sql))
            throw new \Exception('Required first param type string.');

        $ret = new static;
        $ret->_query = $sql;
        $ret->_params = array_merge($ret->_params, $params);

        return $ret;

    }

    public function __toString(){ return $this->createCommand(); }

    public function setDb($_db){

        $this->_db = $_db;
        return $this;

    }

    public function container($class = null){

        if(!is_string($class)) throw new \Exception('$class not string.');
        $this->_class = $class;

        return $this;

    }

    public function createCommand(){

        if($this->_query) return $this->_query;

        $ret = '';

        switch($this->_command){

        case 'SELECT':

            $ret .= $this->_command.' '.$this->_select;
            $ret .= ' FROM '.$this->_table;
            $this->_join && $ret .= ' '.$this->_join;
            $this->_where && $ret .= ' WHERE '.$this->_where;
            $this->_group && $ret .= ' GROUP BY '.$this->_group;
            $this->_order && $ret .= ' ORDER BY '.$this->_order;
            $this->_limit && $ret .= ' LIMIT '.$this->_limit;

            break;
        case 'DELETE':

            $ret .= $this->_command.' FROM '.$this->_table;
            $this->_where && $ret .= ' WHERE '.$this->_where;
            $this->_limit && $ret .= ' LIMIT '.$this->_limit;

            break;
        case 'INSERT INTO':

            $ret .= $this->_command.' '.$this->_table;
            $ret .= ' '.$this->_insert;

            break;
        case 'UPDATE':

            $ret .= $this->_command.' '.$this->_table;
            $ret .= ' SET '.implode(', ', $this->_update);
            $this->_where && $ret .= ' WHERE '.$this->_where;
            $this->_limit && $ret .= ' LIMIT '.$this->_limit;

            break;

        }

        return $ret;

    }

    public function setTable($table = null){

        if($table){
            !preg_match('/^`.+`$/', $table) && $table = '`'.$table.'`';
            $table && $this->_table = $table;
        }

        return $this;

    }

    public function params(){ return $this->_params; }

    public function getDb(){ return $this->_db ?: Config::get('db'); }

    public function get($key = null){

        $query = $this->getDb()->prepare($this->createCommand());
        $query->execute($this->params());
        $this->_error = $query->errorInfo();

        if($this->_class){
            $ret = $query->fetchObject($this->_class);
            return $ret ? $ret->newRecord(false) : new $this->_class;
        }

        $result = $query->fetch(\PDO::FETCH_ASSOC);
        if(!$key) return $result;

        return isset($result[$key]) ? $result[$key] : null;

    }

    public function getAll() {

        $query = $this->getDb()->prepare($this->createCommand());
        $query->execute($this->params());
        $this->_error = $query->errorInfo();

        if ($this->_class) {

            $ret = [];
            while ($obj = $query->fetchObject($this->_class))
                $ret[] = $obj ? $obj->newRecord(false) : new $this->_class;

            return $ret;

        }

        return $query->fetchAll(\PDO::FETCH_ASSOC);

    }

    public function genAll(){

        foreach($this->getAll() as $res)
            yield $res;

    }

    public function execute(){

        $query = $this->getDb()->prepare($this->createCommand());
        $status = $query->execute($this->params());
        $this->_error = $query->errorInfo();
        /* print_r($this->_error); */
        /* die(); */
        return $query->rowCount() ?: $status;
    }

    /**
     * @brief Сбрасывает объект
     * @return Qb
     */

    public function reset(){

        foreach(get_class_vars(__CLASS__) as $key => $value)
            $this->$key = $value;

        return $this;

    }

    /**
     * @brief Настраивает объект на использование команды SELECT.
     * @param $columns Список полей массивом или строкой для выборки.
     * @return Qb
     */

    public function select($columns = '*'){

        $this->_command = 'SELECT';
        $this->columns($columns);

        return $this;

    }

    public function columns($columns = '*') {

        if($this->_command != 'SELECT')
            throw new \Exception('Use in conjunction with select.');

        if(is_array($columns))
            $columns = '`'.implode('`, `', $columns).'`';

        $this->_select = $columns;

        return $this;

    }

    public function count(){

        $query = clone $this;
        $query->_class = null;

        $columns = '*';
        if($query->_group) {

            $columns = 'DISTINCT '.$query->_group;
            $query->_group = null;

        }

        $result = $query->select('COUNT('.$columns.') as `count`')->get();

        return $result['count'];

    }

    public function exists(){ return !!$this->count(); }

    /**
     * @brief Настраивает объект на использование команды DELETE.
     * @param $table Не обязательный параметр с именем таблици.
     * @return Qb
     */

    public function delete(){

        $this->_command = 'DELETE';
        return $this;

    }

    public function from($table = null){

        $this->setTable($table);
        return $this;

    }

    public function insert(Array $columns = []){

        $this->_command = 'INSERT INTO';
        $this->_insert = '(`'.implode('`, `', array_keys($columns)).'`)';

        $tmp = [];
        foreach(array_keys($columns) as $key){
            if(is_null($columns[$key])) $tmp[] = 'NULL';
            else{
                $tmp[] = ':'.$key;
                $this->_params[':'.$key] = $columns[$key];
            }
        }

        $this->_insert .= ' VALUES ('.implode(', ', $tmp).')';
        return $this;

    }

    public function into($table = null){

        $this->setTable($table);
        return $this;

    }

    public function update($columns, Array $params = []){

        $this->_command = 'UPDATE';

        if(is_array($columns))

            foreach($columns as $column => $val){

                if(is_null($val)) $this->_update[] = '`'.$column.'` = NULL';
                else{
                    $this->_update[] = '`'.$column.'` = :'.$column;
                    $this->_params[':'.$column] = $val;
                }

            }

        else {

            $this->_update = [$columns];
            $this->_params = $params;

        }

        return $this;

    }

    public function implode($separator, Array $data = []){

        $result = [];

        foreach ($data as $value) {

            if(is_null($value)) $result[] = 'NULL';
            elseif(is_int($value)) $result[] = $value;
            else $result[] = $this->getDb()->quote($value);

        }

        return implode(', ', $result);

    }

    public function getCondition($condition = null, Array &$params = []){

        if(is_array($condition)){

            $conditions = [];
            $params = [];

            foreach($condition as $key => $value){

                if(is_null($value)) $conditions[] = '( `'.$key.'` IS NULL )';

                elseif(is_array($value)){

                    $conditions[] = '( `'.$key.'` IN ('.$this->implode(', ', $value).') )';

                }
                else {

                    $conditions[] = '( `'.$key.'` = :'.$key.' )';
                    $params[':'.$key] = $value;

                }

            }

            $condition = implode(' AND ', $conditions);

        }

        return $condition ?: '1';

    }

    public function where($condition = null, Array $params = []){

        if($this->_command == 'INSERT INTO')
            throw new \Exception('No using for "insert" method.');

        $condition = $this->getCondition($condition, $params);

        $this->_where = $condition;
        $this->_params = array_merge($this->_params, $params);

        return $this;

    }

    public function andWhere($condition = null, Array $params = []){

        if($this->_command == 'INSERT INTO')
            throw new \Exception('No using for "insert" method.');

        $condition = $this->getCondition($condition, $params);

        $this->_where = '('.$this->_where.') AND ('.$condition.')';
        $this->_params = array_merge($this->_params, $params);

        return $this;

    }

    public function orWhere($condition = null, Array $params = []){

        if($this->_command == 'INSERT INTO')
            throw new \Exception('No using for "insert" method.');

        $condition = $this->getCondition($condition, $params);

        $this->_where = '('.$this->_where.') OR ('.$condition.')';
        $this->_params = array_merge($this->_params, $params);

        return $this;

    }

    public function order($columns){

        if(is_array($columns))
            $columns = implode(', ', $columns);
        $this->_order = $columns;

        return $this;

    }

    public function group($columns){

        if(is_array($columns))
            $columns = '`'.implode('`, `', $columns).'`';
        $this->_group = $columns;

        return $this;

    }

    public function limit($limit){

        !is_array($limit) && $limit = [$limit];
        isset($limit[0]) && $this->_limit = (int)$limit[0];
        isset($limit[1]) && $this->_limit .= ', '.(int)$limit[1];

        return $this;

    }

    public function join($table, $type = null) { 

        if($this->_command != 'SELECT')
            throw new \Exception('Use in conjunction with select.');

        if(!$table)
            throw new \Exception('Table required.');

        if($type) $this->_join .= $type.' ';
        $this->_join .= 'JOIN '.$table;

        return $this;

    }

    public function on($condition = null, Array $params = []) {

        if(!$this->_join)
            throw new \Exception('Use "on" after "join".');

        if(!$condition)
            throw new \Exception('$condition is empty.');

        $condition = $this->getCondition($condition, $params);

        $this->_join .= ' ON '.$condition;
        $this->_params = array_merge($this->_params, $params);

        return $this;

    }

    public function andOn($condition = null, Array $params = []) {

        if(!$this->_join)
            throw new \Exception('Use "on" after "join".');

        if(!$condition)
            throw new \Exception('$condition is empty.');

        $condition = $this->getCondition($condition, $params);

        $this->_join = '('.$this->_join.') AND ('.$condition.')';
        $this->_params = array_merge($this->_params, $params);

        return $this;

    }

    public function orOn($condition = null, Array $params = []) {

        if(!$this->_join)
            throw new \Exception('Use "on" after "join".');

        if(!$condition)
            throw new \Exception('$condition is empty.');

        $condition = $this->getCondition($condition, $params);

        $this->_join = '('.$this->_join.') OR ('.$condition.')';
        $this->_params = array_merge($this->_params, $params);

        return $this;

    }

}
