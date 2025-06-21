<?php


require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/touching_source_model.php';




class Touching_source_frontend
{
    private $touching_source_model;
    public function __construct(Touching_source_model $touching_source_model)
    {
        $this->touching_source_model = $touching_source_model;
    }

   

    public function index()
    {
        $list = $this->touching_source_model->getListFrontend();
        return $list;
    }

   
}



