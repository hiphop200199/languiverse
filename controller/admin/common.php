<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/app.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/account_model.php';
class Common{
    public $account_model,$data;
    public function __construct(Account_model $account_model){
        $this->account_model = $account_model;
    }
    public function init(){
        $auth = $_COOKIE['auth'];
        if(empty($auth)){
            header('Location: ' . ROOT . '/page/admin/login.php');
            exit;
        }
        $auth_array = explode('|',$auth);
        $id = intval($auth_array[0]);
        $account_info = $this->account_model->get($id);
        $login_time = intval($auth_array[2]);
        if(hash('sha256',$account_info['id'].$account_info['account'].$account_info['password'].$account_info['email'].$login_time)!==$auth_array[1]){
            header('Location: ' . ROOT . '/page/admin/login.php');
            exit;
        }
        $this->data['account'] = $account_info;
    }
}