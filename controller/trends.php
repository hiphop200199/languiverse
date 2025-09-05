<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/trends_model.php';

class TrendsFrontend{
    private $trends_model;

    public function __construct(Trends_model $trends_model)
    {
        $this->trends_model = $trends_model;
    }


    public function getList(array $queryArray)
    {
         $age = 0;
        if(!empty($queryArray)){
            $age = intval(substr($queryArray[0], strpos($queryArray[0], '=') + 1));
        }
        $list = $this->trends_model->getListFrontend($age);
        return $list;
    }

    public function get($id)
    {
        $info = $this->trends_model->getFrontend($id);
        return $info;
    }


}

