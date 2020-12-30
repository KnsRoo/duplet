<?php

namespace Websm\Framework\Pager;

/**
 * PagerInterface
 *
 * @author Bykov Igor <con29rus@live.ru>
 */
interface PagerInterface {

    /**
     * getCountPages
     *
     * Вернет количество страниц.
     *
     * @access public
     * @return int;
     */
    public function getCountPages();

    /**
     * getCurrentPage
     *
     * Вернет номер активной страницы.
     *
     * @access public
     * @return int;
     */
    public function getCurrentPage();

    /**
     * chunkItems
     *
     * Вернет срез массива $items текущей страницы.
     *
     * @param Array $items Массив элементов из которого нужно вырезать страницу.
     * @access public
     * @return Array;
     */
    public function chunkItems(Array $items);

    /**
     * getNextPage
     *
     * Вернет номер следующей страницы.
     *
     * @access public
     * @return int;
     */
    public function getNextPage();

    /**
     * getPervPage
     *
     * Вернет номер предыдущей страницы.
     *
     * @access public
     * @return int;
     */
    public function getPervPage();

    /**
     * genPages
     *
     * Вернет массив страниц заданной ширины $width относительно
     * текущей страницы.
     *
     * @param int $width Ширина массива страниц.
     * Ширина обязательно должна быть не четным числом.
     * @access public
     * @return Array;
     *
     * @throws InvalidArgumentException если $width четное число.
     */
    public function genPages($width = 3);

}

