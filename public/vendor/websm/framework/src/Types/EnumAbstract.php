<?php

namespace Websm\Framework\Types;

use ReflectionClass;
use Websm\Framework\Exceptions\NotFoundException;
use Websm\Framework\Exceptions\InvalidArgumentException;
use Exception;

/**
 * EnumAbstract 
 *
 * Класс для создания переяисляемых типов.
 *
 * @code
 *
 * class NumbersEnum extends EnumAbstract {
 *
 *  const ONE = 1;
 *  const TWO = 2;
 *  const THREE = 3;
 *  const FOUR = 4;
 *
 * }
 *
 * // По имени константы:
 * $number = new NumbersEnum('TWO');
 *
 * // По значению одной из констант:
 * $number = NumbersEnum::valueOf(2);
 *
 * function printNumber(NumbersEnum $number) {
 *
 *  echo $number;
 *
 * // или
 *
 *  echo $number->get();
 *
 * }
 *
 * @endcode
 * 
 * @abstract
 * @author Быков Игорь <con29rus@live.ru>
 */
abstract class EnumAbstract {

    /**
     * current 
     *
     * Хранит текущее значение типа.
     * 
     * @var mixed
     * @access private
     */
    private $current;

    /**
     * __construct 
     * 
     * @param string $key Имя одной из объявленныъх констант.
     * @final
     * @access public
     * @return void
     * @throws NotFoundException если константа не определена в перечислении.
     * @throws InvalidArgumentException если $key не строка.
     */
    final public function __construct($key) {

        if (!is_string($key))
            throw new InvalidArgumentException('$key is not string.');

        $key = mb_strtoupper($key, 'UTF-8');
        $class = get_class($this);

        try {

            $value = constant($class.'::'.$key);

        } catch (Exception $e) {

            throw new NotFoundException("${key} is not defined.");

        }

        if (is_null($value))
            throw new NotFoundException("${key} is not defined.");

        $this->current = $value;

    }

    /**
     * __toString 
     *
     * Метод для автоматического перевода объекта в строку.
     * 
     * @final
     * @access public
     * @return string
     */
    final public function __toString() {

        return (String)$this->current; 

    }

    /**
     * get 
     *
     * Вернет текущее значение типа.
     * 
     * @final
     * @access public
     * @return mixed
     */
    final public function get() {

        return $this->current; 

    }

    /**
     * valueOf 
     *
     * Создает перечисляемый тип по значению одной из констант
     * 
     * @param mixed $value 
     * @static
     * @final
     * @access public
     * @return EnumAbstract
     * @throws NotFoundException если значения нет в перечислении.
     */
    final public static function valueOf($value) {

        $class = get_called_class();
        $reflectionClass = new ReflectionClass($class);
        $constants = $reflectionClass->getConstants();

        $key = array_search($value, $constants);

        if ($key) {

            return new static($key);

        } else {

            throw new NotFoundException("${value} is not defined.");

        }

    }

    /**
     * iter
     *
     * Вернет ключи и значения перечисление в виде массива.
     *
     * @static
     * @final
     * @access public
     * @return Array
     */
    final public static function iter() {

        $class = get_called_class();
        $reflectionClass = new ReflectionClass($class);

        return $reflectionClass->getConstants();

    }

    /**
     * indexOf
     *
     * Выполняет поиск ключа по значению.
     *
     * @param mixed $needle Искомое значение.
     * @static
     * @final
     * @access public
     * @return mixed
     * @see array_search
     */
    final public static function indexOf($needle) {

        $class = get_called_class();
        $reflectionClass = new ReflectionClass($class);
        $haystack = $reflectionClass->getConstants();

        return array_search($needle, $haystack);

    }

    /**
     * inEnum
     *
     * Проверяет есть ли значение в перечислении.
     *
     * @param mixed $needle Проверяемое значение.
     * @static
     * @final
     * @access public
     * @return Bool
     */
    final public static function inEnum($needle) {

        $class = get_called_class();
        $reflectionClass = new ReflectionClass($class);
        $haystack = $reflectionClass->getConstants();

        return in_array($needle, $haystack);

    }

}
