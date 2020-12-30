<?php

namespace Websm\Framework\MessageQueue;

/**
 * TaskInterface 
 * 
 * @package 
 * @author Bykov Igor <con29rus@live.ru>
 *
 */
interface TaskInterface {

    /**
     * __construct 
     *
     * Создание объекта задачи.
     * 
     * @param Array $data Массив ключ => значение
     * @param TypeEnum $type Тип сообщения 
     * в виде перечисления TypeEnum.
     * @access public
     * @return void
     *
     * @code
     *
     * // Описание полей структуры $data:
     * $data = [
     *  'message' => '', // сообщение в виде строки, массива или объекта.
     *  'sleep' => 1000, // задержка перед исполнением в милисекундах.
     *  'trys' => 5, // количество попыток для задания.
     * ];
     *
     * @endcode
     */
    public function __construct(Array $data, TypeEnum $type);

    /**
     * getMessage
     *
     * Получение сообщения задачи.
     * 
     * @access public
     * @return mixed
     */
    public function getMessage();

    /**
     * toJSON 
     *
     * Возврат задачи в виде JSON строки.
     * 
     * @access public
     * @return string
     */
    public function toJSON();

    /**
     * decTrys 
     *
     * Для уменьшения количества попыток
     * 
     * @access public
     * @return void
     */
    public function decTrys();

    /**
     * sleep 
     *
     * Останавливает исполнение на заданное в задаче количество секунд.
     * 
     * @access public
     * @return void
     */
    public function sleep();

}
