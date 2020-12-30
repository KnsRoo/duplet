<?php

namespace Websm\Framework\Locker;

interface LockerInterface {

    /**
     * lock 
     *
     * Метод для блокировки.
     * 
     * @param int $time время блокирования в секундах.
     * @access public
     * @return void
     */
    public function lock($time);

    /**
     * isLocked 
     *
     * Возвратит стус блокировки.
     * 
     * @access public
     * @return Bool
     */
    public function isLocked();

    /**
     * decAttempts 
     *
     * Для уменьшения количества попыток.
     * 
     * @access public
     * @return void
     */
    public function decAttempts();

    /**
     * getAviableAttempts 
     *
     * Вернёт количество доступных попыток.
     * 
     * @access public
     * @return int
     */
    public function getAviableAttempts();

    /**
     * clearState 
     *
     * Сбросит состояние блокировки.
     * 
     * @access public
     * @return void
     */
    public function clearState();

    /**
     * setId 
     *
     * Установит id для блокировки.
     * 
     * @param mixed $id 
     * @access public
     * @return void
     */
    public function setId($id);

    /**
     * getLockTime 
     *
     * Вернет остаток времени блокировки в секундах.
     * 
     * @access public
     * @return void
     */
    public function getLockTime();

}
