<?php

namespace Websm\Framework;

class Validator {

    const RULE_SCHEMA = ['field', 'validator' , 'on' => '', 'message' => ''];
    const ERROR_MESSAGE = 'Проверьте правильность заполнения поля "${field}".';

    protected $_scenario = 'default';
    protected $_scenarios = [];
    protected $_ruleSchema = ['field', 'validator' , 'on' => '', 'message' => ''];
    protected $_errorMsg = 'Проверьте правильность заполнения поля "${field}".';
    protected $_errors = [];

    public function __construct($scenario = 'default'){

        if(!is_string($scenario))
            throw new \Exception('Scenario is not string.');

        $this->_scenario = $scenario;
        $this->_scenarios[$scenario] = [];

    }

    public function __get($key){

        $property = '_'.$key;
        if(!isset($this->$property))
            throw new \Exception('Missing field "'.$key.'" in this form.');
        return $this->$property;

    }

    public function getFields() {

        $ret = [];
        foreach ($this->_scenarios[$this->_scenario] as $key => $value) {
            $ret[$key] = $this->$key;
        }
        return $ret;

    }

    public function scenario($scenario){

        $this->_scenario = $scenario; 
        return $this;

    }

    /**
     * @brief Парсер правил обработки полей.
     * @param $rules Массив правил.
     * @return Void
     *
     * Пример массива правил:
     *
     * @code
     *  $form = new Form;
     *  $form->makeRules([
     *      ['title', 'clear', 'on' => 'saveForm'],
     *      ['text', 'pass'],
     *      ...
     *  ]);
     * @endcode
     */

    public function makeRules(Array $rules = []){

        foreach($rules as $rule)

            if(isset($rule[0]) && isset($rule[1])) {

                $rule[0] = is_array($rule[0]) ? $rule[0] : [$rule[0]];
                $rule[1] = is_array($rule[1]) ? $rule[1] : [$rule[1]];
                $scenarios = isset($rule['on']) ? $rule['on'] : $this->_scenario;
                $scenarios = is_array($scenarios) ? $scenarios : [$scenarios]; 

                foreach($scenarios as $scenario){

                    foreach($rule[0] as $field)

                        $this->_scenarios[$scenario][trim($field)][] = [
                            'validator' => $rule[1],
                            'params' => array_diff_key($rule, self::RULE_SCHEMA),
                            'message' => isset($rule['message']) ? $rule['message'] : self::ERROR_MESSAGE,
                        ];

                }

            }

    }

    /**
     * @brief Метод описывается пользователем,
     * должен возвращать массив правил.
     * @return Array
     *
     * Пример массива правил:
     *
     * @code
     *  [
     *      ['title', 'clear', 'on' => 'saveForm'],
     *      ['text', 'pass'],
     *      ...
     *  ];
     * @endcode
     *
     * Синтаксис одного правила:
     *
     * @code
     *  ['поле', 'валидатор', 'on' => 'сценарий', дополнительные параметры ... ];
     * @endcode
     */

    protected function getRules(){ return []; }

    /* Стандартные валидаторы */

    protected function pass(){ return true; }

    protected function reject(){ return false; }

    protected function required($field, $params = []){
        return strlen($this->$field) > 0;
    }

    protected function boolean($field, $params = []){

        $bool = filter_var($this->$field, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if(is_null($bool)) return false;
        $this->$field = (int) $bool;

    }

    protected function email($field, $params = []){

        if($this->required($field))
            return filter_var($this->$field, FILTER_VALIDATE_EMAIL) ? true : false;

    }

    protected function phone($field, $params = []){

        if($this->required($field))
            if (preg_match('/^(8|\+?7)?\s?\(?(\d{3})\)?\s?(\d{3})\-?(\d{2})\-?(\d{2})$/', $this->$field, $out)) {
                $this->$field = implode(array_slice($out, 2), '');
            }
            else return false;

    }

    protected function length($field, $params = []){

        $len = mb_strlen($this->$field, 'UTF-8');
        $params = array_merge(['min' => 0, 'max' => $len], $params);
        return $len >= $params['min'] && $len <= $params['max'];

    }

    protected function native($field, $params = []){

        if(!$this->required($field)){
            $form = new $this;
            $this->$field = $form->$field;
            unset($form);
        }

    }

    protected function setValue($field, $params = []){

        $params = array_merge(['value' => ''], $params);
        $this->$field = $params['value'];

    }

    protected function striplace($field, $params = []){

        $this->$field = filter_var($this->$field, FILTER_SANITIZE_STRING, FILTER_FLAG_ENCODE_AMP);

    }

    protected function stripTags($field, $params = []){

        $this->$field = strip_tags($this->$field);

    }

    protected function match($field, $params = []){

        $params = array_merge(['exp' => '//'], $params);
        return !!preg_match($params['exp'], $this->$field);

    }

    protected function numeric($field, $params = []){

        return is_numeric($this->$field);

    }

    protected function int($field, $params = []){

        if(preg_match('/^\d*$/', $this->$field)){
            $this->$field = (int)$this->$field;
            return true;
        }
        else return false;

    }

    protected function count($field, $params = []){

        $params = array_merge(['min' => 0, 'max' => $this->$field], $params);
        return count($this->$field) >= $params['min'] && count($this->$field) <= $params['max'];

    }

    protected function limit($field, $params = []){

        $params = array_merge(['min' => 0, 'max' => $this->$field], $params);
        return $this->$field >= $params['min'] && $this->$field <= $params['max'];

    }

    protected function compare($field, $params = []){

        return $this->$field === $this->{$params['cmp']};

    }

    protected function date($field, $params = []){

        $params = array_merge(['from' => 'd.m.Y', 'to' => 'Y-m-d H:i:s'], $params);
        $dateTime = \DateTime::createFromFormat($params['from'], $this->$field);
        if(!$dateTime) return false;
        $this->$field = $dateTime->format($params['to']);

    }

    /**
     * @brief Метод запускающий проверку формы.
     * @return Bool
     */

    public function validate(){

        $this->makeRules($this->getRules());

        $scenario = &$this->_scenarios[$this->_scenario];
        $scenario = $scenario ?: [];
        foreach($scenario as $field => $rules)

            foreach($rules as $rule){

                foreach($rule['validator'] as $validator){

                    $result = call_user_func([$this, $validator], $field, $rule['params']);
                    if($result === false) $this->genError($field, $rule['message']);

                }
            }

        return !$this->_errors;

    }

    protected function genError($field, $message = '', $type = 'error'){

        $message = preg_replace('/\$\{field\}/', $field, $message);

        !isset($this->_errors[$field]) && $this->_errors[$field] = [];
        $this->_errors[$field][] = ['text' => $message, 'type' => $type];

    }

    public function getErrors(){

        return $this->_errors;

    }

    public function getError(){

        return array_shift($this->_errors);

    }

    protected function isSecure($k = ''){

        return isset($this->_scenarios[$this->_scenario][$k]);

    }

    /**
     * @brief Метод для массового присваивания значений формы.
     * @param $data Массив правил.
     * @return Void
     *
     * @code
     * $form = new MyForm;
     * $form->bind($_POST['myForm]);
     * $form->validate();
     * @endcode
     */

    public function bind(Array $data = []){

        foreach($data as $k => $v) $this->$k = $v;
        return $this;

    }

}
