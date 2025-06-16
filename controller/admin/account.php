<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/controller/admin/common.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/account_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/app.php';

class Account extends Common
{
   
    public function __construct(Account_model $account_model){
        parent::__construct($account_model);
        parent::init();
    }

    public function requestEntry()
    {
        $request_body = file_get_contents('php://input');
    
        $data = json_decode($request_body, true);
        
        switch ($data['task']) {
          
            case 'create':
                $this->create($data);
                break;
            case 'edit':
                $this->edit($data);
                break;
            case 'delete':
                $this->delete($data);
                break;
            case 'edit-password':
                $this->editPassword($data);
                break;    
        }
    }

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

    private function create($data) {
        $account = $data['account'];
        $email = $data['email'];
        $checkResult = $this->account_model->checkByAccountAndEmail($account,$email);
        if(!empty($checkResult)){
            $response = json_encode(['errCode'=>ACCOUNT_DUPLICATED]);
            echo $response;
            exit;
        }
        $nickname = $data['name'];
        $password = hash('sha256', $data['password']);
        $status = intval($data['status']);
        $isAdmin = intval($data['is_admin']);
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

    private function edit($data) {
        $id = intval($data['id']);
        $account = $data['account'];
        $email = $data['email'];
        $nickname = $data['name'];
        $status = intval($data['status']);
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

    private function delete($data) {
        $id = intval($data['id']);
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

    private function editPassword($data){
        $id = intval($data['id']);
        $newPassword = hash('sha256', $data['newPassword']);
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

$account->requestEntry();

unset($account);