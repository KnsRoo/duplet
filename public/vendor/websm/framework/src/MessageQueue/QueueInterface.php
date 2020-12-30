<?php

namespace Websm\Framework\MessageQueue;

/**
 * QueueInterface 
 * 
 * @author Igor Bykov <con29rus@live.ru> 
 */
interface QueueInterface {

    /**
     * pushTask 
     *
     * Кладет в очередь объект Task
     * 
     * @param Task $task подготовленный объект.
     * @access public
     * @return bool
     */
    public function pushTask(Task $task);

    /**
     * push 
     *
     * Кладет в очередь сообщение.
     * Тип сообщения определяется автоматически.
     * 
     * @param mixed $message Строка, массив или объект.
     * @param int $sleep Задержка перед исполнением задачи.
     * @param int $trys Разрешенное количество попыток для выполнения задачи.
     * @access public
     * @return bool
     */
    public function push($message, $sleep = 1000, $trys = 5);

    /**
     * shift 
     *
     * Получает вынимает задачу из очереди.
     * Применяется только в worker'e.
     * 
     * @access public
     * @return Task
     * @throws TypeErrorException если тип сообщения не JSON 
     * или не подходит по формату.
     */
    public function shift();

}
