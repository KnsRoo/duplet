<?php

namespace Websm\Framework;

use Websm\Framework\Validation\Validator;

abstract class Form extends Validator {

    public static function init($scenario = null) {

        return new static($scenario);

    }

    public function __construct($scenario = null) {

        parent::__construct($scenario);
        $this->rules($this);

    }

    abstract public function rules($validator);

    public function validate() {

        $reserved = get_class_vars(__CLASS__);

        foreach($this as $field => $value) {

            if(isset($reserved[$field])) continue;
            $this->$field = &$this->data[$field];

        }

        return parent::validate();

    }

}
