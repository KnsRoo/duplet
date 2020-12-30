<?php

namespace Websm\Framework\Path;

use Iterator;

/**
 * PathQueueInterface
 *
 * Интерфейс очереди пути.
 *
 * @uses Iterator
 * @author Bykov Igor <con29rus@live.ru>
 */
interface PathQueueInterface extends Iterator {

    /**
     * Constructor
     *
     * @param Array $items
     * @access public
     */
    public function __construct(Array $items = []);

    /**
     * push
     *
     * Добавляет часть пути в конец очереди.
     *
     * @param PathItemInterface $item Часть пути
     * @access public
     * @return PathQueueInterface;
     */
    public function push(PathItemInterface $item);

    /**
     * pop
     *
     * Достает последний элемент из очереди
     *
     * @access public
     * @return PathItem;
     */
    public function pop();

    /**
     * unshift
     *
     * Добавляет часть пути в начало очереди.
     *
     * @param PathItemInterface $item Часть пути
     * @access public
     * @return PathQueueInterface;
     */
    public function unshift(PathItemInterface $item);

    /**
     * shift
     *
     * Достает перывй элемент из очереди
     *
     * @access public
     * @return PathItem;
     */
    public function shift();

    /**
     * reverse
     *
     * Перевернет очередь.
     *
     * @access public
     * @return PathQueueInterface;
     */
    public function reverse();

    /**
     * extend
     *
     * Расширяет текущую очередь из другой очереди.
     *
     * @param PathQueueInterface $queue другая очередь
     * @access public
     * @return PathQueueInterface;
     */
    public function extend(PathQueueInterface $queue);

} // End function pop // End function shift
