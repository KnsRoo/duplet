<?php

namespace Websm\Framework\Di\Service;

use Websm\Framework\Di\Container;

interface ServiceInterface {

    /**
     * @brief Конструктор.
     * @param $service Сервис.
     * @return Void
     */

    public function __construct($service, Container $container = null);

    /**
     * @brief Устанавливает флаг доступности сервиса в виде синглтона.
     * @return ServiceInterface
     */

    public function shared($status = true);

    /**
     * @brief Проверяет доступен ли сервис в виде синглтона.
     * @return Boolean
     */

    public function isShared();

    /**
     * @brief Сеттер для иньекции контейнера.
     * @return Void
     */

    public function setDi(Container $di);

    /**
     * @brief Геттер для получения контейнера.
     * @return Container
     */

    public function getDi();

    /**
     * @brief Собирает сервис.
     * @return Mixed
     */

    public function build(...$argv);

    /**
     * buildShared
     *
     * Собирает сервис или возвращает уже построенный.
     * 
     * @access public
     * @return void
     */
    public function buildShared(...$argv);

}
