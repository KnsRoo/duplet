<?php

namespace Websm\Framework\Search;

use Websm\Framework\Exceptions\BaseException as Exception;

/**
 * Service 
 *
 * Сервис для объединения поисковых движков.
 * 
 */
class Service {

    private $engines = [];
    private $named = [];
    private $query = '';
    private $returnType = 'HTML';

    /**
     * addEngine 
     *
     * Добавляет движок для поиска.
     * 
     * @param EngineInterface $engine 
     * @access public
     * @return Service
     */
    public function addEngine(EngineInterface $engine) {

        $this->engines[] = $engine;

        $name = $engine->getName();

        if (!$name || !is_string($name))
            throw new Exception('$name is not type string.');

        $this->named[$name] = $engine;

        return $this;

    }

    /**
     * setQuery 
     *
     * Устанавливает запрос для поиска.
     * 
     * @param mixed $query 
     * @access public
     * @return Service
     */
    public function setQuery($query) {

        $this->query = $query;
        return $this;

    }

    public function setReturnType(TypesEnum $type) {

        $this->returnType = (string)$type;
        return $this;
    
    }

    /**
     * getCount 
     *
     * Вернет количество результатов поиска.
     *
     * @param string $name Не обязательный параметр для 
     * уточнения поиска по определённому движку.
     * 
     * @access public
     * @return Int
     */
    public function getCount($name = null) {

        if ($name) {

            $engine = $this->named[$name];
            return $engine->count($this->query);

        }

        $count = 0;

        foreach ($this->engines as $engine) {

            $count += $engine->count($this->query);

        }

        return $count;

    }

    /**
     * getResult 
     *
     * Вернет результаты поиска в виде верстки.
     * 
     * @param string $name Не обязательный параметр для 
     * уточнения поиска по определённому движку.
     *
     * @access public
     * @return String
     *
     * @code
     *
     * $search = new Search\Service;
     * $search->addEngine(CatalogSearchEngine);
     *
     * $search->query('query search string');
     *
     * echo $search->getCount(); // Integer
     * echo $search->getResult(); // String
     *
     * @endcode
     */
    public function getResult($name = null) {

        $ret = [];
        $returnType = new TypesEnum($this->returnType);

        if ($name) {

            $engine = $this->named[$name];
            $results = $engine->find($this->query, $returnType);
            $ret = array_merge($ret, $results);
            return $ret;

        }

        foreach ($this->engines as $engine) {

            $results = $engine->find($this->query, $returnType);
            $ret = array_merge($ret, $results);

        }

        return $ret;

    }

}
