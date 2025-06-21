<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/touching_model.php';




class Touching_frontend{
 private $touching_model;
    public function __construct(Touching_model $touching_model)
    {
        $this->touching_model = $touching_model;
    }

    public function requestEntry()
    {
        $request_body = file_get_contents('php://input');
    
        $data = json_decode($request_body, true);
      
        switch ($data['task']) {
            case 'create-thought':
                $this->createThought($data);
                break;
        } 
    }

    public function getList(array $queryArray)
    {
        $sourceId = 0;
        if(!empty($queryArray)){
            $sourceId = intval(substr($queryArray[0], strpos($queryArray[0], '=') + 1));
        }
        $list = $this->touching_model->getListFrontend($sourceId);
        return $list;
    }

    public function get($id)
    {
        $info = $this->touching_model->getFrontend($id);
        return $info;
    }

    public function randomTouching()
    {
        $info = $this->touching_model->getRandomTouching();
        return $info;
    }


   private function createThought($data)
    {
        $id = intval($data['id']);
        $thought = $data['thought'];
        $result = $this->touching_model->createThought($id,$thought);
        if($result === SUCCESS){
            $response = json_encode(['errCode'=>SUCCESS]);
            echo $response;
            exit;
        }
        $response = json_encode(['errCode'=>SERVER_INTERNAL_ERROR]);
        echo $response;
        exit;
    }

}

$url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$query = parse_url($url, PHP_URL_QUERY);
$query_array = explode('&', $query);

$query_array = array_map(function ($item) {
    $item = substr($item, strpos($item, '=') + 1);
    return $item;
}, $query_array);

$db = new Db();

$touching = new Touching_frontend(new Touching_model($db));

$touching->requestEntry();

unset($touching);