<?php

namespace Websm\Framework\Path;

/**
 * PathItemInterface
 *
 * Интерфейс части пути.
 *
 * @package 
 * @author Bykov Igor <con29rus@live>
 */
interface PathItemInterface {

    /**
     * getTitle
     *
     * Вернет наименование части пути.
     *
     * @access public
     * @return String;
     */
    public function getTitle();

    /**
     * getRef
     *
     * Вернет ссылку части пути.
     *
     * @access public
     * @return String;
     */
    public function getRef();

}
