<?php


require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/curse_category_model.php';




class Curse_category_frontend
{
    private $curse_category_model;
    public function __construct(Curse_category_model $curse_category_model)
    {
        $this->curse_category_model = $curse_category_model;
    }

   

    public function index()
    {
        $list = $this->curse_category_model->getListFrontend();
        return $list;
    }

   
}



