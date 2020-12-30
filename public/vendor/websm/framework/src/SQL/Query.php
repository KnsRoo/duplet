<?php

namespace Websm\Framework\SQL;

use PDO;
use Exception;

class Query {

	private $connection;
	private $cotainer;
	private $insert = '';
	private $update = '';
	private $where = '';
	private $limit = '';
	private $group = '';
	private $order = '';
	private $columns = '';
	private $table = '';
	private $query = '';
	private $command = '';
	private $params = [];
	private $join = '';

	public static function table($table) {

		$object = new self;
		$objects->setTable($table);
		return $object;

	}

	public static function query($query, Array $params = []) {

		if (!is_string($query))
			throw new Exception('$query is not string.');

		$object = new self;
		$object->query = $query;
		$object->params = $params;
		return $this;

	}

	public function __toString() { return $this->build(); }

	public function build() {

		if ($this->query) return $this->query;

		$query = '';

		switch ($this->command) {

			case 'SELECT': $query = $this->buildSelect(); break;

			case 'INSERT INTO': $query = $this->buildInsert(); break;

			case 'UPDATE': $query = $this->buildUpdate(); break;

			case 'DELETE': $query = $this->buildDelete(); break;

			default: throw new Exception('undefindet command.'); break;

		}

		return $query;

	}

	private function buildSelect() {

		$ret = $this->command.' '.$this->columns;
		$ret .= ' FROM '.$this->table;
		if ($this->join) $ret .= ' '.$this->join;
		if ($this->where) $ret .= ' WHERE '.$this->where;
		if ($this->group) $ret .= ' GROUP BY '.$this->group;
		if ($this->order) $ret .= ' ORDER BY '.$this->order;
		if ($this->limit) $ret .= ' LIMIT '.$this->limit;

		return $ret;

	}

	private function buildInsert() {

		$ret = $this->command.' '.$this->table;
		$ret .= ' '.$this->insert;

		return $ret;

	}

	private function buildUpdate() {

		$ret = $this->command.' '.$this->table;
		$ret .= ' SET '.$this->update;
		if ($this->where) $ret .= ' WHERE '.$this->where;
		if ($this->limit) $ret .= ' LIMIT '.$this->limit;

		return $ret;

	}

	private function buildDelete() {

		$ret = $this->command.' FROM '.$this->table;
		if ($this->where) $ret .= ' WHERE '.$this->where;
		if ($this->limit) $ret .= ' LIMIT '.$this->limit;

		return $ret;

	}

	public function setConnection($connection) {

		$this->connection = $connection;
		return $this;

	}

	public function getConnection() { return $this->connection; }

	public function setContainer($container) {

		$this->container = $container;
		return $this;

	}

	public function setTable($table) {

		if (!is_string($table))
			throw new Exception('$table is not string.');

		if (!preg_match('/^`.+`$/', $table))
			$table = '`'.$table.'`';

		$this->table = $table;
		return $this;

	}

	public function select($columns = '*') {

		$this->command = 'SELECT';

		if (is_array($columns))
			$columns = '`'.implode('`, `', $columns).'`';

		$this->columns = $columns;
		return $this;

	}

	public function insert(Array $data = []) {

		$this->command = 'INSERT INTO';
		$columns = array_keys($columns);
		$this->insert = '(`'.implode('`, `', $columns).'`)';

		$temp = [];
		foreach ($columns as $key => $value) {

			if (is_null($columns[$key])) $temp[] = 'NULL';
			else{

				$temp[] = ':'.$key;
				$this->params[':'.$key] = $value;

			}

		}

		$this->insert .= ' VALUES ('.implode(', ', $temp).')';
		return $this;

	}

	public function update($columns, Array $params = []) {

		$this->command = 'UPDATE';

		if (is_array($columns)) {

			$temp = [];
			foreach ($columns as $column => $value) {

				if (is_null($value))
					$temp[] = '`'.$column.'` = NULL';

				else{

					$temp[] = '`'.$column.'` = :'.$column;
					$this->params[':'.$column] = $value;

				}

			}

			$this->update = implode(', ', $temp);

		} else {

			$this->update = $columns;
			$this->params = $params;

		}

		return $this;

	}

	public function delete() {

		$this->command = 'DELETE';
		return $this;

	}

	public function from($table) {

		$this->setTable($table);
		return $this;

	}

	public function into($table = null){

		$this->setTable($table);
		return $this;

	}

	private function implode($separator, Array $data = []){

		$result = [];

		foreach ($data as $value) {

			if (is_null($value)) $result[] = 'NULL';
			elseif (is_int($value)) $result[] = $value;
			else $result[] = $this->getConnection()->quote($value);

		}

		return implode(', ', $result);

	}

	private function getCondition($condition = null, Array &$params = []){

		if (!is_array($condition)) return $condition ?: '1';

		$conditions = [];
		$params = [];

		foreach ($condition as $key => $value) {

			if (is_null($value)) $conditions[] = '( `'.$key.'` IS NULL )';

			elseif (is_array($value)) {

				$conditions[] = '( `'.$key.'` IN ('.$this->implode(', ', $value).') )';

			} else {

				$conditions[] = '( `'.$key.'` = :'.$key.' )';
				$params[':'.$key] = $value;

			}

		}

		$condition = implode(' AND ', $conditions);

		return $condition ?: '1';

	}

	public function where($condition = null, Array $params = []) {

		if ($this->command == 'INSERT INTO')
			throw new Exception('No using for "insert" method.');

		$condition = $this->getCondition($condition, $params);

		$this->where = $condition;
		$this->params = array_merge($this->params, $params);

		return $this;

	}

	public function andWhere($condition = null, Array $params = []) {

		if ($this->command == 'INSERT INTO')
			throw new Exception('No using for "insert" method.');

		$condition = $this->getCondition($condition, $params);

		$this->where = '('.$this->where.') AND ('.$condition.')';
		$this->params = array_merge($this->params, $params);

		return $this;

	}

	public function orWhere($condition = null, Array $params = []) {

		if ($this->command == 'INSERT INTO')
			throw new Exception('No using for "insert" method.');

		$condition = $this->getCondition($condition, $params);

		$this->where = '('.$this->where.') OR ('.$condition.')';
		$this->params = array_merge($this->params, $params);

		return $this;

	}

	public function order($columns) {

		if (is_array($columns)) $columns = implode(', ', $columns);

		$this->order = $columns;

		return $this;

	}

	public function group($columns) {

		if (is_array($columns))
			$columns = '`'.implode('`, `', $columns).'`';

		$this->group = $columns;

		return $this;

	}

	public function limit($limit) {

		if (!is_array($limit)) $limit = [$limit];

		if (isset($limit[0])) $this->limit = (int)$limit[0];
		if (isset($limit[1])) $this->limit .= ', '.(int)$limit[1];

		return $this;

	}

	public function get() {

		$connection = $this->connection;
		$query = $connection->prepare($this->build());
		$query->execute($this->params);

		if ($this->container) {

			$ret = $query->fetchObject($this->container);
			if ($ret) $ret->newRecord(false);
			else $ret = new $this->container;
			return $ret;

		}

		return $query->fetch(PDO::FETCH_ASSOC);

	}

	public function getAll() {

		$connection = $this->connection;
		$query = $connection->prepare($this->build());
		$query->execute($this->params);

		if ($this->container) {

			$ret = [];
			while($obj = $query->fetchObject($this->container)) {

				$obj->newRecord(false);
				$ret[] = $obj;

			}

			return $ret;

		}

		return $query->fetchAll(PDO::FETCH_ASSOC);

	}

	public function genAll() {

		foreach($this->getAll() as $res) yield $res;

	}

	public function execute() {

		$connection = $this->connection;

		$query = $connection->prepare($this->build());
		$status = $query->execute($this->params);
		return $query->rowCount() ?: $status;

	}

	public function count() {

		$query = clone $this;
		$query->container = null;

		$columns = '*';
		if ($query->group) {

			$columns = 'DISTINCT '.$query->group;
			$query->group = null;

		}

		$result = $query->select('COUNT('.$columns.') as `count`')->get();

		return $result['count'];

	}

	public function exists() { return !!$this->count(); }


	public function join($table, $type = null) { 

		if ($this->command != 'SELECT')
			throw new Exception('Use in conjunction with select.');

		if (!$table) throw new Exception('Table required.');

		if ($type) $this->join .= $type.' ';
		$this->join .= 'JOIN '.$table;

		return $this;

	}

	public function on($condition = null, Array $params = []) {

		if (!$this->join) throw new Exception('Use "on" after "join".');

		if (!$condition) throw new Exception('$condition is empty.');

		$condition = $this->getCondition($condition, $params);

		$this->join .= ' ON '.$condition;
		$this->params = array_merge($this->params, $params);

		return $this;

	}

	public function andOn($condition = null, Array $params = []) {

		if (!$this->join) throw new Exception('Use "on" after "join".');

		if (!$condition) throw new Exception('$condition is empty.');

		$condition = $this->getCondition($condition, $params);

		$this->join = '('.$this->join.') AND ('.$condition.')';
		$this->params = array_merge($this->params, $params);

		return $this;

	}

	public function orOn($condition = null, Array $params = []) {

		if (!$this->join) throw new Exception('Use "on" after "join".');

		if (!$condition) throw new Exception('$condition is empty.');

		$condition = $this->getCondition($condition, $params);

		$this->join = '('.$this->join.') OR ('.$condition.')';
		$this->params = array_merge($this->params, $params);

		return $this;

	}

}
