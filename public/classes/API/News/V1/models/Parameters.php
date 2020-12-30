<?php
namespace API\News\V1\models; 

use API\News\V1\SortEnum;
use API\News\V1\OrderEnum;
use Core\Misc\Validator;
use \DateTime;

class Parameters extends Validator {

    public $limit = 50;
    public $page = 1;
    public $sort = 'def';
    public $order = 'ASC';
    public $fromDate = '01.01.1970'; 
    public $toDate = '01.01.2031';

    public function getRules(){

        return [
            [ ['limit', 'page'], 'length', 'max' => 5, 'min' => 1,
                'message' => 'max length'
            ],
            [['limit', 'page'], 'int'],
            ['limit', 'limit', 'max' => 50],
            ['sort', 'striplace'],
            ['order', 'striplace'],
            ['fromDate', 'date'],
            ['toDate', 'date'],
        ];

    }

    public function getValidateParams() {

        if ($this->validate()) {

            return [
                'limit' => $this->limit, 
                'page' => $this->page, 
                'sort' => $this->sort = new SortEnum($this->sort), 
                'order' => $this->order = new OrderEnum($this->order), 
                'fromDate' => $this->fromDate,
                'toDate' => $this->toDate
            ];

        } else {

            return ['Errors' => $this->getError()];

        }

    }

}
