<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/common.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/account_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/app.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/util.php';

class Account extends Common
{
   
    public function __construct(Account_model $account_model){
        parent::__construct($account_model);
        parent::init();
    }

    

    //如果是管理員，回傳所有帳號資料，否則只回傳該使用者的資料
    public function index()
    {
        $id = intval($this->data['account']['id']);
        $is_admin = intval($this->data['account']['is_admin']);
       if($is_admin == IS_ADMIN){
            $list = $this->account_model->getList();
            return $list;
       }
       $list = [];
       $list[] = $this->account_model->get($id);
       return $list;
    }

    public function get($id){
        $info = $this->account_model->get($id);
        return $info;
    }

    private function create() {
        $account = htmlspecialchars(strip_tags($_POST['account']));
        $email = filter_var($_POST['email'],FILTER_VALIDATE_EMAIL);
        if(!$email){
            $response = json_encode(['errCode'=>EMAIL_FORMAT_ERROR]);
            echo $response;
            exit;
        }
        $checkResult = $this->account_model->checkByAccountAndEmail($account,$email);
        if(!empty($checkResult)){
            $response = json_encode(['errCode'=>ACCOUNT_DUPLICATED]);
            echo $response;
            exit;
        }
        $nickname = htmlspecialchars(strip_tags($_POST['name']));
        $password = hash('sha256', $_POST['password']);
        $status = intval($_POST['status']);
        $isAdmin = intval($_POST['is_admin']);
        $result = $this->account_model->create($account,$password,$nickname,$email,$status,$isAdmin);
        if($result===SUCCESS){
            $response = json_encode(['errCode'=>SUCCESS,'redirect'=>'list.php']);
           echo $response;
           exit;
        }
        $response = json_encode(['errCode'=>SERVER_INTERNAL_ERROR]);
        echo $response;
        exit;
    }

    private function edit() {
        $id = intval($_POST['id']);
        $account = htmlspecialchars(strip_tags($_POST['account']));
        $email = filter_var($_POST['email'],FILTER_VALIDATE_EMAIL);
        if(!$email){
            $response = json_encode(['errCode'=>EMAIL_FORMAT_ERROR]);
            echo $response;
            exit;
        }
        $nickname = htmlspecialchars(strip_tags($_POST['name']));
        $status = intval($_POST['status']);
        $checkExist = $this->account_model->get($id);
        if(empty($checkExist)){
            $response = json_encode(['errCode'=>CURSE_CATEGORY_NOT_EXIST]);
            echo $response;
            exit;
        }
        $result = $this->account_model->edit($id,$account,$nickname,$email,$status);
        if($result===SUCCESS){
            $response = json_encode(['errCode'=>SUCCESS,'redirect'=>'list.php']);
           echo $response;
           exit;
        }
        $response = json_encode(['errCode'=>SERVER_INTERNAL_ERROR]);
        echo $response;
        exit;  
    }

    private function delete() {
        $id = intval($_POST['id']);
        $result = $this->account_model->delete($id);
        if($result===SUCCESS){
            $response = json_encode(['errCode'=>SUCCESS,'redirect'=>'list.php']);
           echo $response;
           exit;
        }
        $response = json_encode(['errCode'=>SERVER_INTERNAL_ERROR]);
        echo $response;
        exit;      
    }

    private function editPassword(){
        $id = intval($_POST['id']);
        $newPassword = hash('sha256', $_POST['newPassword']);
        $checkExist = $this->account_model->get($id);
        if(empty($checkExist)){
            $response = json_encode(['errCode'=>CURSE_CATEGORY_NOT_EXIST]);
            echo $response;
            exit;
        }
        $result = $this->account_model->editPassword($id,$newPassword);
        if($result===SUCCESS){
            $response = json_encode(['errCode'=>SUCCESS,'redirect'=>'list.php']);
           echo $response;
           exit;
        }
        $response = json_encode(['errCode'=>SERVER_INTERNAL_ERROR]);
        echo $response;
        exit;  
    }
}


$account = new Account(new Account_model(new Db()));

Util::requestEntry(object: $account);

unset($account);