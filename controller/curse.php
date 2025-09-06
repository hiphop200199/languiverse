<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/config/constant.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/model/curse_model.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/util/util.php';

class CurseFrontend{
    private $curse_model;

    public function __construct(Curse_model $curse_model)
    {
        $this->curse_model = $curse_model;
    }

    


    public function getList()
    {
        $list = $this->curse_model->getListFrontend();
        return $list;
    }

    public function get($id)
    {
        $info = $this->curse_model->getFrontend($id);
        return $info;
    }

    private function createStrategy()
    {
        $id = intval($_POST['id']);
        $content = $_POST['content'];
        $result = $this->curse_model->createStrategy($id,$content);
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

$curse  = new curseFrontend(new curse_model(new Db()));

Util::requestEntry($curse);

unset($curse);