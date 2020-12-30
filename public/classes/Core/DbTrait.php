<?php

	namespace Core;

	trait DbTrait{

		protected $limit = '';
		protected $groupBy = '';
		protected $orderBy = '';
		protected $fields = '*';
		protected $where = '';

		/**
		 * @brief Устанавливает лимиты запроса.
		 * @param $limit Массив из двух или одного значения лимита.
		 *
		 * @code
		 * 	$this->setLimit([10, 20]); //Установить лимит в 20 позиций начиная с 10й позиции
		 * 	$this->setLimit([20]); //Установить лимит в 20 позиций
		 * @endcode
		 */

		public function setLimit(Array $limit){
			$limit = count($limit)>2 ? array_slice($limit, 0, 2) : $limit;
			$limit = implode(',',$limit);
			$this->limit = preg_match('/^\d+(\,\d+)?$/',$limit) ? 'LIMIT '.$limit : '';
		}

		/**
		 * @brief Устанавливает группировку запроса.
		 * @param $groupBy Массив из одного или больше значении.
		 *
		 * @code
		 * 	$this->setGroupBy(['name', 'sort']);
		 * @endcode
		 */

		public function setGroupBy(Array $groupBy){
			$groupBy = implode(',',$groupBy);
			$this->groupBy = preg_match('/^[\d\_\w\`]+$/',$groupBy) ? 'GROUP BY '.$groupBy : '';
		}

		/**
		 * @brief Устанавливает сортировку запроса.
		 * @param $orderBy Массив из одного или больше значении.
		 *
		 * @code
		 * 	$this->setOrderBy(['name ASC', 'sort', 'summ DESC']);
		 * @endcode
		 */

		public function setOrderBy(Array $orderBy){
			$tmp='';
			foreach($orderBy as &$val){
				$tmp.= preg_match('/^[\d\_\w\`]+(\s+(ASC|DESC))?$/i',$val) ? $val.', ' : '';
			}
			$this->orderBy=$tmp ? 'ORDER BY '.preg_replace('/\,\s?$/','',$tmp) : '';
		}

		/**
		 * @brief Устанавливает столбцы для выборки.
		 * @param $fields Массив из одного или больше значении.
		 *
		 * @code
		 * 	$this->setFields(['name', 'sort', 'summ']);
		 * @endcode
		 */

		public function setFields(Array $fields){
			$fields = implode(',',$fields);
			$this->fields = preg_match('/^[\d\_\w\`]+$/',$fields) ? $fields : '*';
		}

		/**
		 * @brief Устанавливает условие для выборки. 
		 * @param $method Метод объединения условий.
		 * @param $where Условие в виде строки.
		 *
		 * @code
		 * 	$this->setWhere(['AND', '(`summ`>=3000 OR `summ`<=5000)']);
		 * @endcode
		 */

		public function setWhere($method='', $where=''){
			$where=$method.' '.$where;
			$this->where=preg_match('/^\s*((AND|OR)\s)?.{5,}$/i',$where) ? $where : '';
		}

	}
