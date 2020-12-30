<?php

namespace Websm\Framework\Di\Service;

use Websm\Framework\Di\InjectionContainerInterface;
use Websm\Framework\Di\RawArgument;
use Websm\Framework\Di\Container;

use Websm\Framework\Di\Exceptions\TypeException;
use Websm\Framework\Di\Exceptions\BaseException;

use ReflectionMethod;
use ReflectionException;

class ServiceClass extends ServiceAbstract {

    protected $arguments = [];
    protected $methods = [];
    protected $properties = [];

    public function __construct($service, Container $di = null) {

        foreach (class_implements($service) as $interface) {

            if ($di && !$di->has($interface))
                $di->add($interface, $this);

        }

        parent::__construct($service, $di);

    }

    /**
     * arguments 
     *
     * Назначает аргументы для конструктора сервиса.
     * 
     * @param Array $arguments 
     * @access public
     * @return ServiceClass
     */

    public function arguments(Array $arguments = []) {

        $this->arguments = $arguments;
        return $this;

    }

    /**
     * methodCall 
     *
     * Добавляет вызов метода в очередь.
     * При сборке сервиса будут вызваны все методы из очереди
     * с разрешением их зависимостей.
     * 
     * @param mixed $method Имя метода.
     * @param Array $arguments Массив аргументов.
     * @access public
     * @return ServiceClass
     */

    public function methodCall($method, Array $arguments = []) {

        if (!$method || !is_string($method))
            throw new TypeException('$method is not string.');

        $this->methods[$method] = $arguments;
        return $this;

    }

    /**
     * propertySet 
     *
     * Добавляет иньекцию через свойство объекта в очередь.
     * При сборке сервиса будет осуществлена иньекция
     * всех свойств из очереди.
     * 
     * @param mixed $name Имя свойства.
     * @param mixed $value Значение свойства.
     * @access public
     * @return void
     */

    public function propertySet($name, $value = null) {

        if (!$name || !is_string($name))
            throw new TypeException('$name is not string.');

        $this->properties[$name] = $value;
        return $this;

    }

    /**
     * build 
     *
     * Выполняет сборку сервиса и разрешает зависимости.
     * 
     * @access public
     * @return Object
     */

    public function build(...$argv) {

        $service = &$this->service;

        if ($argv) $arguments = $argv;

        elseif ($this->arguments)
            $arguments = $this->buildArguments($this->arguments);

        else $arguments = $this->getReflectionArguments($service);

        $object = new $service(...$arguments);

        if ($object instanceof InjectionContainerInterface)
            $object->setDi($this->container);

        $this->setterInjection($object);
        $this->propertyInjection($object);

        return $object;

    }

    /**
     * getReflectionArguments 
     * 
     * @param string $class 
     * @param string $methodName 
     * @access private
     * @return void
     */

    private function getReflectionArguments($class, $methodName = '__construct') {

        try { $method = new ReflectionMethod($class, $methodName); }

        catch (ReflectionException $e) { return []; }

        $arguments = [];

        foreach ($method->getParameters() as $param) {

            $type = $param->getClass();

            if ($type && $typeName = $type->getName())
                $arguments[] = $this->container->get($typeName);

            elseif ($param->isOptional())
                $arguments[] = $param->getDefaultValue();

            else $arguments[] = null;

        }

        return $arguments;

    }

    /**
     * buildArguments 
     * 
     * @param Array $arguments 
     * @access private
     * @return void
     */

    private function buildArguments(Array $arguments) {

        foreach ($arguments as &$argument) {

            if ($argument instanceof RawArgument)
                $argument = $argument->getData();

            else $argument = $this->container->get($argument);

        }

        return $arguments;

    }

    /**
     * propertyInjection 
     * 
     * @param Object $object 
     * @access private
     * @return void
     */

    private function propertyInjection($object) {

        foreach ($this->properties as $property => $value) {

            $arguments = $this->buildArguments(['value' => $value]);
            $object->$property = $arguments['value'];

        }

    }

    /**
     * setterInjection 
     * 
     * @param Object $object 
     * @access private
     * @return void
     */

    private function setterInjection($object) {

        foreach ($this->methods as $method => $arguments) {

            if ($arguments)
                $arguments = $this->buildArguments($arguments);

            else {

                $arguments = $this->getReflectionArguments(
                    $this->service, $method);

            }

            $object->$method(...$arguments);

        }

    }

}
