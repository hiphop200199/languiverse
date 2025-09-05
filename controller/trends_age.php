<?php


require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/trends_age_model.php';




class Trends_age_frontend
{
    private $trends_age_model;
    public function __construct(Trends_age_model $trends_age_model)
    {
        $this->trends_age_model = $trends_age_model;
    }

   

    public function index()
    {
        $list = $this->trends_age_model->getListFrontend();
        return $list;
    }

   
}



