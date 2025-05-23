<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/model/account_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';


class Account_frontend 
{
   private $account_model;
    public function __construct(Account_model $account_model){
        $this->account_model = $account_model;
    }

   

    public function index()
    {   
        $list = $this->account_model->getListFrontend();
        return $list;
    }

}
