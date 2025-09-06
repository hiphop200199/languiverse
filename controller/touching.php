<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/touching_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/util.php';



class Touching_frontend{
 private $touching_model;
    public function __construct(Touching_model $touching_model)
    {
        $this->touching_model = $touching_model;
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


   private function createThought()
    {
        $id = intval($_POST['id']);
        $thought = $_POST['thought'];
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


$query_array = Util::getSearchQuery();

$query_array = Util::getSearchQueryValue($query_array);

$db = new Db();

$touching = new Touching_frontend(new Touching_model($db));

Util::requestEntry($touching);

unset($touching);