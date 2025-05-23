<?php


require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/joke_category_model.php';




class Joke_category_frontend
{
    private $joke_category_model;
    public function __construct(Joke_category_model $joke_category_model)
    {
        $this->joke_category_model = $joke_category_model;
    }

   

    public function index()
    {
        $list = $this->joke_category_model->getListFrontend();
        return $list;
    }

   
}



