<?php

namespace Websm\Framework\Path;

/**
 * PathProviderInterface
 *
 * Интрфейс для построения пути до элемента
 * реализующего данный интерфейс.
 *
 * @author Bykov Igor <con29rus@live.ru>
 */
interface PathProviderInterface {

    /**
     * getParent
     *
     * Вернет родительский элемент или null.
     *
     * @access public
     * @return PathProviderInterface;
     */
    public function getParent();

    /**
     * getTitle
     *
     * Вернет загорловк для отображения.
     *
     * @access public
     * @return String;
     */
    public function getTitle();

    /**
     * getRef
     *
     * Вернет ссылку для доступа к элементу.
     *
     * @access public
     * @return String;
     */
    public function getRef();

    /**
     * isRoot
     *
     * Вернет true если эелемнт является корневым
     *
     * @access public
     * @return Boolean;
     */
    public function isRoot();

}
