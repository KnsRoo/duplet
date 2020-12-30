<?php

namespace Back\FilesMin;

use Websm\Framework\Validator;

class Options extends Validator{

    public $multiple = false;
    public $checkable = false;
    public $draggable = false;
    public $optional = false;
    public $setValue = '';
    public $onchange = '';
    public $types = '';

    public function getRules(){

        return [

            [
                [
                    'multiple',
                    'checkable',
                    'optional',
                    'draggable',
                    'onchange',
                    'types',
                    'setValue',
                ],
                'native'
            ],
            [ ['multiple', 'checkable', 'draggable', 'optional'], 'boolean'],

        ];

    }

    public function getTypes() {

        $ret = [];

        if($this->types) { $ret = explode(',', $this->types); }
        return $ret;

    }

}

