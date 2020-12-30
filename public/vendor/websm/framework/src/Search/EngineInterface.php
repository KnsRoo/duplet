<?php

namespace Websm\Framework\Search;

interface EngineInterface {

    /**
     * getName
     *
     * Вернет имя поискового движка.
     * 
     * @access public
     * @return String
     */
    public function getName();

    /**
     * count 
     *
     * Вернет количество результатов по запросу.
     * 
     * @param String $query 
     * @access public
     * @return Int
     */
    public function count($query);

    /**
     * find 
     *
     * Вернет результаты в виде строки.
     * 
     * @param String $query 
     * @param TypesEnum $returnType
     * @access public
     * @return String
     */
    public function find($query, TypesEnum $returnType);

}
