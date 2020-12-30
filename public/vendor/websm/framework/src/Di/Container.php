<?php

namespace Websm\Framework\Di;

use Websm\Framework\Di\Exceptions\NotFoundException;
use Websm\Framework\Di\Exceptions\InvalidArgumentException;

class Container {

    use SingletonTrait;

    private $services = [];

    /**
     * @brief Добавляет сервис в контейнер.
     * @param $name Имя сервиса.
     * @param $service Сам сервис в виде любых данных.
     * @param $shared Флаг доступности созданного сервиса в виде синглтона.
     * @return ServiceInterface
     * @throws InvalidArgumentException если $name не строкового типа.
     *
     * @code
     *  $di = new Container;
     *
     *  $di->add('first', function($di) {
     *      return new FirstClass;
     *  });
     *
     *  $di->add('second', 'SecondClass');
     *
     *  $di->add('third', 'ThirdClass')
     *      ->methodCall('setFour');
     *      ->methodCall('setSecond', ['second']);
     *      ->methodCall('setAnyData', [
     *          'anyData',
     *          new RawArgument(123);
     *      ])
     *      ->propertySet('rand', new RawArgument(123));
     *
     *  $di->add('four', new FourClass)
     *
     * @endcode
     */

    public function add($name, $service = null, $shared = false) {

        if (!$name || !is_string($name))
            throw new InvalidArgumentException('$name is not string.');

        if (!$service) $service = $name;

        $service = $this->solution($service);
        $this->services[$name] = $service;

        $service->shared($shared);
        $service->setDi($this);

        return $service;

    }

    /**
     * @brief Добавляет сервис в контейнер в виде синглтона.
     * @see add
     */

    public function addShared($name, $service = null) {

        return $this->add($name, $service, true);

    }

    /**
     * @brief Решает к какой категории отнести сервис.
     * @param $service Сам сервис в виде любых данных.
     * @return ServiceInterface
     */

    private function solution($service) {

        if ($service instanceof Service\ServiceInterface)
            return $service;

        if ($service instanceof RawArgument)
            return new Service\ServiceAny($service->getData(), $this);

        if (is_string($service) && class_exists($service))
            return new Service\ServiceClass($service, $this);

        if (is_callable($service) && $service instanceof \Closure)
            return new Service\ServiceClosure($service, $this);

        if (is_object($service))
            return new Service\ServiceObject($service, $this);

        return new Service\ServiceAny($service, $this);

    }

    /**
     * @brief Получает сервис по имени.
     * @param $name Имя сервиса.
     * @return Mixed
     * @throws InvalidArgumentException если $name не строкового типа.
     * @throws NotFoundException если сервис не найден.
     *
     * @code
     *  $di = new Container;
     *  $di->add('SomeInterface', 'SomeClass');
     *  $service = $di->get('SomeInterface);
     * @endcode
     */

    public function get($name, ...$argv) {

        if (!$name || !is_string($name))
            throw new InvalidArgumentException('$name is not string.');

        $services = &$this->services;

        if (!empty($services[$name])) {

            $service = $services[$name];

            if ($service->isShared())
                return $service->buildShared(...$argv);

            else return $service->build(...$argv);

        } elseif (class_exists($name)) {

            $this->add($name);
            return $this->get($name, ...$argv);

        }

        throw new NotFoundException('Service not found.');

    }

    /**
     * @brief Получает сервис в виде настраевомого объекта по имени.
     * @param $name Имя сервиса.
     * @return ServiceInterface
     * @throws InvalidArgumentException если $name не строкового типа.
     * @throws NotFoundException если сервис не найден.
     */

    public function getService($name) {

        if (!$name || !is_string($name))
            throw new InvalidArgumentException('$name is not string.');

        if (!empty(self::$services[$name]))
            return self::$services[$name];

        else throw new NotFoundException('Service not found.');

    }

    /**
     * @brief Проверяет существование сервиса по имени.
     * @param $name Имя сервиса.
     * @return Boolean
     * @throws InvalidArgumentException если $name не строкового типа.
     */

    public function has($name) {

        if (!$name || !is_string($name))
            throw new InvalidArgumentException('$name is not string.');

        return !empty($this->services[$name]);

    }

}
