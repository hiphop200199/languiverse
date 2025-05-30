<?php


require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/flower_meaning_model.php';



class Flower_meaning_frontend
{
    private $flower_meaning_model;
    public function __construct(Flower_meaning_model $flower_meaning_model)
    {
        $this->flower_meaning_model = $flower_meaning_model;
    }

    

    public function index()
    {
        $list = $this->flower_meaning_model->getListFrontend();
        return $list;
    }

    public function get($id)
    {
        $info = $this->flower_meaning_model->getFrontend($id);
        return $info;
    }

}

