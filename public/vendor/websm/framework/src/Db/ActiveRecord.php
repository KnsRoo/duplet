<?php

namespace Websm\Framework\Db;
use Websm\Framework\Db\Config;
use Websm\Framework\Validator;

class ActiveRecord extends Validator {

    public static $table = null;

    public $_error = [];

    protected $_new = true;
    protected $_strict = true;
    protected $_deleted = false;
    protected $_meta = [];
    protected $_pk = [];

    public static function find($condition = null, Array $params = []){

        return self::query()
            ->select('*')
            ->where($condition, $params)
            ->container(get_called_class());

    }

    public static function query(){

        return Qb::table(static::getTable())
            ->setDb(static::getDb());

    }

    public function __set($field, $value){

        $meta = &$this->getMeta();

        if(!isset($meta[$field]))
            throw new \Exception('Missing field "'.$field.'"');
        $this->$field = $value;

    }

    public function __get($field){

        $methodName = mb_convert_case($field, MB_CASE_TITLE, 'UTF-8');
        $methodName = preg_replace('/\W/u', '', $methodName);
        $methodName = 'get'.$methodName;

        if(method_exists($this, $methodName)){

            return call_user_func([$this, $methodName]);

        }

        $meta = &$this->getMeta();
        if(!isset($meta[$field]))
            throw new \Exception('Missing field "'.$field.'"');

        return null;

    }

    protected function initMysqlMeta(){

        $query = Qb::query('SHOW COLUMNS FROM `'.$this->getTable().'`');
        $meta = $query->setDb(static::getDb())->genAll();

        foreach($meta as $data){

            $this->_meta[$data['Field']] = $data;

            if($data['Key'] == 'PRI'){
                $this->{$data['Field']} = isset($this->{$data['Field']})
                    ? $this->{$data['Field']}
                    : null;
                $this->_pk[$data['Field']] = &$this->{$data['Field']};
            }

        }

    }

    protected function initSqliteMeta(){

        $query = Qb::query('PRAGMA table_info(`'.$this->getTable().'`)');
        $meta = $query->setDb(static::getDb())->genAll();

        foreach($meta as $data){

            $this->_meta[$data['name']] = $data;

            if($data['pk']){
                $this->{$data['name']} = $this->{$data['name']} ?: null;
                $this->_pk[$data['name']] = &$this->{$data['name']};
            }

        }

    }

    public function initMeta(){

        switch(static::getDb()->type()){

        case 'mysql':
            $this->initMysqlMeta();
            break;

        case 'sqlite':
            $this->initSqliteMeta();
            break;

        default:
            $this->initMysqlMeta();
            break;

        }

    }

    public function &getMeta(){

    if(!$this->_meta) $this->initMeta();
    return $this->_meta;

    }

    public function newRecord($status = true){

        $this->_new = !!$status;
        return $this;

    }

    public function strict($state = true){

        $this->_strict = $state;
        return $this;

    }

    public function isNewRecord(){ return $this->_new; }

    public function isNew(){ return $this->_new; }

    public function isDeleted(){ return $this->_deleted; }

    public function getQuery(){

        $query = Qb::table(static::getTable())
            ->setDb(static::getDb());
        $this->_error = &$query->_error;

        if($this->_new) $query->insert($this->getFields());
        else $query->update($this->getFields())->where($this->_pk);
        return $query;

    }

    public function exists($condition = null, Array $params = []){

        if(!$condition) $condition = $this->_pk;

        return Qb::table(static::getTable())
            ->setDb(static::getDb())
            ->select()
            ->where($condition, $params)
            ->exists();

    }

    public function save(){

        if(!$this->_meta) $this->initMeta();

        if(!$this->_pk)
            throw new \Exception('No public keys.');

        if($this->_strict && !$this->validate()) return false;
        $result = $this->getQuery()->execute();

        if(!$result && $this->_error[1] == 1062)
            ActiveRecord::genError('title', 'Запись с таким названием уже существует');
        if($result) $this->newRecord(false);
        return $result;

    }

    public function delete(){

        if(!$this->_meta) $this->initMeta();

        if(!$this->_pk)
            throw new \Exception('No public keys.');

        if($this->isNew())
            throw new \Exception('Cannot delete missing record.');

        $query = Qb::table(static::getTable())
            ->setDb(static::getDb())
            ->delete()
            ->where($this->_pk);

        $this->_error = &$query->_error;
        $result = $query->execute();

        if($result){

            $this->_deleted = true;
            $this->_new = true;

        }

        return $result;

    }

    public static function getDb(){ return Config::get('db'); }

    public static function getTable(){

        if(static::$table) return static::$table;
        else{
            $class = preg_replace('/^.*(\w+)$/iuU', '$1', get_called_class());
            return mb_strtolower($class, 'UTF-8');
        }

    }

    public function bind(Array $data = []){

        foreach($data as $k => $v){
            try{ $this->__set($k, $v); }
            catch(\Exception $e){ continue; }
        }

    }

    public function getFields() {

        if($this->_strict) return parent::getFields();

        $ret = [];

        foreach ($this->getMeta() as $key => $value)
            isset($this->$key) && $ret[$key] = $this->$key;

        return $ret;

    }

}
