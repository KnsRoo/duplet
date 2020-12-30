<?php

namespace Core\Misc\NewPath;

use Websm\Framework\Db\ActiveRecord;
use Websm\Framework\Response;

class NewPath extends Response {

    protected $_ar;
    protected $_data;

    protected $_aliases = [
        'id'    => 'id',
        'cid'   => 'cid',
        'chpu'  => 'chpu',
        'title' => 'title',
    ];

    protected $_templates = [
        'layout' => __DIR__.'/temp/layout.tpl',
        'item'   => __DIR__.'/temp/item.tpl',
    ];

    public static function init($ar = null) {

        $object = new self;
        if($ar) $object->setActiveRecord($ar);

        return $object;

    }

    public function __toString(){

        return $this->getHtml();

    }

    /**
     * setAliases
     * @brief Задаёт объекту Алиасы
     *
     * @param Array $aliases
     *
     * @code
     * $alliases = [
        'id'    => 'index',
        'cid'   => 'catecory',
        'chpu'  => 'url',
        'title' => 'name',
     * ];
     * $path = NewPath::init();
     * $path->setAliases($alliases);
     * @endcode
     */

    public function setAliases(Array $aliases) {

        $this->_aliases = array_merge($this->_aliases, $aliases);
        unset($this->_aliases['ar']);
        return $this;

    }

    /**
     * setTemplates
     * 
     * @brief Задаёт пути к шаблонам
     * 
     * @param Array $templates
     *
     * @code
     * $templates = [
     *   'layout' => __DIR__.'/layout.tpl',
     *   'item'   => __DIR__.'/item.tpl'
     * ]
     * $path = NewPath::init();
     * $path->setTemplates($teplates);
     * @endcode
     */

    public function setTemplates(Array $templates) {

        $this->_templates = array_merge($this->_templates, $templates);
        return $this;

    }

    /**
     * setActiveRecord
     *
     * @brief Задаёт объекту объект типа ActiveRecord с которы он будет работать
     *
     * @param ActiveRecord $ar
     *
     * @code
     * $path = NewPath::init();
     * $path->setActiveRecord($ar);
     * @endcode
     */

    public function setActiveRecord($ar) {

        if(!($ar instanceof ActiveRecord))
            throw new \Exception('Object not extended ActiveRecord .');

        $this->_ar = $ar;

        return $this;

    }

    public function setData(Array $data){

        $this->_data = $data;
        return $this;

    }

    /**
     * getPathElement
     * 
     * @brief Создаёт массив элементов пути
     *
     * @return Array $buffer
     *
     * @code
     * $path = NewPath::init();
     * $path->setActiveRecord($ar);
     * $path->getPathElement();
     * @endcode
     */

    public function getPathElement() {

        if(!($this->_ar instanceof ActiveRecord))
            throw new \Exception('Missing ActiveRecord object.');

        $buffer = [];

        if(!$this->_ar->isNew()) $this->genQueue($this->_ar, $buffer);

        return $buffer;

    }

    /**
     * genQueue
     *
     * @brief Рекурсивный метод для создания массива с элементами путя
     *
     * @param ActiveRecord $ar
     * @param Array $aliases
     * @param Array $buffer
     */

    protected function genQueue($ar, Array &$buffer) {
        if($ar->cid){
            $parent = $ar::find(['id' => $ar->cid])->get();
            $this->genQueue($parent, $buffer);
        }
        $buffer[] = $ar;
    }

    /**
     * getHtml
     *
     * @brief Возврощает конечную вёрстку
     * 
     * @return String
     *
     * @code
     * $path = NewPath::init();
     * $path->setActiveRecord($ar);
     * echo $path->getHtml();
     * @endcode
     */

    public function getHtml() {

        if(!($this->_ar instanceof ActiveRecord))
            throw new \Exception('Missing ActiveRecord object.');

        $queue = $this->getPathElement();
        $items = '';
        $position = 1;

        foreach($queue as $element) {

            $data = [
                'item'     => $element,
                'position' => $position,
                'aliases'  => $this->_aliases,
                'data'     => $this->_data,
            ];

            $items .= $this->render($this->_templates['item'], $data);
            $position++;

        }

        return $this->render($this->_templates['layout'], ['items' => $items, 'data' => $this->_data]);

    }

}
