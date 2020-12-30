<?php

namespace Websm\Framework;

use Websm\Framework\Db\ActiveRecord;
use Websm\Framework\Db\Qb;

class Sort {

    protected $_ar;
    protected $_in = [];
    protected $_cidField = 'cid';
    protected $_scenario = 'sort';
    protected $_sortField = 'sort';

    private final function __construct() { }

    /**
     * @brief Создает объект для последующих манипуляций с положением записи.
     * @param $ar объект расширяемый ActiveRecord с предварительно найденной записью.
     * @param $cidField  наименование столбца отвечающего за принадлежность к категории.
     * @param $sortField наименование столбца сортировки.
     * @return Sort
     *
     * Пример использования:
     *
     * @code
     *
     * Sort::init($ar, 'cid', 'sort')->normalise();
     *
     * @endcode
     */

    public static function init($ar, $cidField = 'cid', $sortField = 'sort') {

        if(!($ar instanceof ActiveRecord))
            throw new \Exception('Object not extended ActiveRecord .');

        if(!is_string($cidField) || !is_string($sortField))
            throw new \Exception('$cidField or $sortField is not type String .');

        if(!$cidField || !$sortField)
            throw new \Exception('$cidField or $sortField is empty string.');

        $obj = new self;
        $obj->_ar = $ar;

        $obj->_cidField = $cidField;
        $obj->_sortField = $sortField;

        if(property_exists($ar, $cidField)) $obj->in($ar->$cidField);

        return $obj;

    }

    /**
     * @brief Устанавливает категорию в которой будет производиться перемещение.
     * @param $in идентификатор категории или массив содержащий поле категории и ее идентификатор.
     * @return Sort
     */

    public function in($in = null){

        $this->_in = is_array($in) ? $in : [ $this->_cidField => $in ];

        return $this;

    }

    /**
     * @brief Приводит значения сортировок к нормальному виду.
     * @return Sort
     *
     * Пример использования:
     *
     * @code
     *
     *  // Нормализируем значения сортировок без указания категории.
     *
     *  Sort::init($ar)->normalise();
     *
     *  // Нормализируем значения сортировок в главной категории.
     *
     *  Sort::init($ar)->in(null)->normalise();
     *
     *  // Нормализируем знвчения сортировок
     *  // в категории с идентификатором '30a14dd2b9c91e523a28e6e0476e039b' и явным указанием поля категории.
     *
     *  Sort::init($ar)->in(['cid' => '30a14dd2b9c91e523a28e6e0476e039b'])->normalise();
     *
     * @endcode
     */

    public function normalise() {

        $ar = $this->_ar;
        $field = $this->_sortField;

        $gen = $ar::find($this->_in)
            ->order([$field])
            ->genAll();

        $i = 1;
        foreach ($gen as $obj) {
            $obj->scenario('sort');
            $obj->$field = $i;
            /* $obj->preview = '123'; */
            $obj->save();
            /* echo $obj->sort."\r\n"; */
            $i++;
        }
        /* die(); */

        return $this;

    }

    /**
     * @brief Выставляет новую позицию сортировки.
     * @param $pos Новая позиция сортировки.
     * @return Sort
     *
     * Пример использования:
     *
     * @code
     *
     *  // Перемещаем раздел на позицию 5.
     *
     *  Sort::init($ar)->move(5);
     *
     *  // Перемещаем раздел из корневой категории на позицию 5.
     *
     *  Sort::init($ar)->in(null)->move(5);
     *
     *  // Перемещаем раздел, из категории с иденитфикатором '30a14dd2b9c91e523a28e6e0476e039b',
     *  // явным указанием поля категории, на позицию 5 с последующей нормализацией сортировок.
     *
     *  Sort::init($ar)
     *      ->in(['cid' => '30a14dd2b9c91e523a28e6e0476e039b'])
     *      ->move(5)
     *      ->normalise();
     *
     * @endcode
     */

    public function move($pos) {

        $ar = $this->_ar;

        if($ar->isDeleted())
            throw new \Exception('Cannot move deleted record.');

        if($ar->isNew())
            throw new \Exception('Cannot move missing record.');

        $field = $this->_sortField;
        $oldSort = $ar->$field;

        if($oldSort >= $pos){

            $where = '`'.$field.'` >= :pos AND `'.$field.'` <= :sort';
            $update = '`'.$field.'` = `'.$field.'` + 1';

        }
        else {

            $where = '`'.$field.'` <= :pos AND `'.$field.'` >= :sort';
            $update = '`'.$field.'` = `'.$field.'` - 1';

        }

        $result = $ar::query()
            ->update($update)
            ->where($this->_in)
            ->andWhere(
                $where,
                [':pos' => $pos, ':sort' => $oldSort]
            )
            ->execute();

        if(!$result)
            throw new \Exception('Shift Other items failed: '.implode(' ', $ar->_error));

        $ar->scenario($this->_scenario);
        $ar->sort = (int)$pos;
        if(!$ar->save()) throw new \Exception('Moving failed. See ActiveRecord Object.');

        return $this;

    }

}
