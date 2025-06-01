<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/curse_model.php';

class CurseFrontend{
    private $curse_model;

    public function __construct(Curse_model $curse_model)
    {
        $this->curse_model = $curse_model;
    }

     public function requestEntry()
    {
        $request_body = file_get_contents('php://input');
    
        $data = json_decode($request_body, true);
      
        switch ($data['task']) {
            case 'create-strategy':
                $this->createStrategy($data);
                break;
        } 
    }
    


    public function getList(array $queryArray)
    {
        $category = intval(substr($queryArray[0], strpos($queryArray[1], '=') + 1));
    

        $list = $this->curse_model->getListFrontend($category);
        return $list;
    }

    public function get($id)
    {
        $info = $this->curse_model->getFrontend($id);
        return $info;
    }

    private function createStrategy($data)
    {
        $id = intval($data['id']);
        $score = intval($data['score']);
        $comment = $data['comment'];
        $result = $this->curse_model->createStrategy($id,$comment,$score);
        if($result === SUCCESS){
            $response = json_encode(['errCode'=>SUCCESS]);
            echo $response;
            exit;
        }else{
             $response = json_encode(['errCode'=>SERVER_INTERNAL_ERROR]);
            echo $response;
            exit;
        }
    }

}

$curse  = new curseFrontend(new curse_model(new Db()));

$curse->requestEntry();

unset($curse);